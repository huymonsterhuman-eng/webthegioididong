<?php

namespace App\Http\Controllers;

use App\Models\ChatbotMessage;
use App\Models\ChatbotSession;
use App\Services\ChatBotToolService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ChatBotController extends Controller
{
    protected ChatBotToolService $tools;

    public function __construct(ChatBotToolService $tools)
    {
        $this->tools = $tools;
    }

    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
            'history' => 'array|max:20',
            'history.*.role' => 'in:user,model',
            'history.*.text' => 'string|max:2000',
        ]);

        $apiKey = trim((string) config('services.gemini.api_key'));
        $model = trim((string) config('services.gemini.model'));
        $endpoint = rtrim((string) config('services.gemini.endpoint'), '/');

        if (empty($apiKey)) {
            return response()->json([
                'error' => 'Chatbot chưa được cấu hình. Vui lòng liên hệ quản trị viên.',
            ], 503);
        }

        $systemPrompt = $this->buildSystemPrompt();

        // Build initial conversation từ history + user message mới
        $conversation = [];
        foreach ($validated['history'] ?? [] as $msg) {
            $conversation[] = [
                'role' => $msg['role'],
                'parts' => [['text' => $msg['text']]],
            ];
        }
        $conversation[] = [
            'role' => 'user',
            'parts' => [['text' => $validated['message']]],
        ];

        // ===== LOG: mở/tạo session + ghi tin nhắn user =====
        $chatSession = $this->openChatSession($request);
        $this->logMessage($chatSession, 'user', $validated['message']);
        $startTime = microtime(true);
        $lastToolCalled = null;

        // Function calling loop (tối đa 5 turns)
        $maxTurns = 5;
        for ($turn = 0; $turn < $maxTurns; $turn++) {
            $response = $this->callGeminiWithRetry(
                "{$endpoint}/{$model}:generateContent?key={$apiKey}",
                [
                    'systemInstruction' => ['parts' => [['text' => $systemPrompt]]],
                    'tools' => $this->tools->getToolsDeclaration(),
                    'contents' => $conversation,
                    'generationConfig' => [
                        'temperature' => 0.5,
                        'maxOutputTokens' => 500,
                    ],
                ]
            );

            if ($response === null) {
                return response()->json([
                    'error' => 'Chatbot không phản hồi kịp. Vui lòng thử lại.',
                ], 504);
            }

            if (!$response->successful()) {
                Log::error('Chatbot Gemini API error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                if ($response->status() === 429) {
                    $retryDelay = 30;
                    if (preg_match('/"retryDelay":\s*"(\d+)s"/', $response->body(), $m)) {
                        $retryDelay = (int) $m[1];
                    }
                    return response()->json([
                        'error' => "Chatbot đang bận. Vui lòng thử lại sau {$retryDelay} giây.",
                    ], 429);
                }

                if ($response->status() === 503) {
                    return response()->json([
                        'error' => 'Máy chủ AI đang quá tải. Anh/chị vui lòng thử lại sau 1-2 phút.',
                    ], 503);
                }

                if ($response->status() === 400 || $response->status() === 403) {
                    return response()->json([
                        'error' => 'Chatbot chưa được cấu hình đúng. Vui lòng liên hệ quản trị viên.',
                    ], 500);
                }

                return response()->json([
                    'error' => 'Chatbot tạm thời không phản hồi. Vui lòng thử lại sau.',
                ], 500);
            }

            $parts = $response->json('candidates.0.content.parts', []);
            $finishReason = $response->json('candidates.0.finishReason');

            if (empty($parts)) {
                return response()->json([
                    'reply' => 'Xin lỗi anh/chị, em chưa hiểu ý câu hỏi. Anh/chị có thể diễn đạt lại giúp em không ạ?',
                ]);
            }

            // Gemini có thể trả về nhiều part: functionCall(s) + text.
            // Nếu có functionCall → execute tất cả rồi loop lại.
            $functionCalls = array_filter($parts, fn ($p) => isset($p['functionCall']));

            if (!empty($functionCalls)) {
                // Sanitize: convert empty `args` array to object {} — Gemini Proto rejects array for empty args
                $sanitizedParts = array_map(function ($part) {
                    if (isset($part['functionCall'])) {
                        if (empty($part['functionCall']['args'] ?? null)) {
                            $part['functionCall']['args'] = new \stdClass();
                        }
                    }
                    return $part;
                }, $parts);

                // Append model turn (giữ nguyên parts của model để giữ context)
                $conversation[] = [
                    'role' => 'model',
                    'parts' => $sanitizedParts,
                ];

                // Execute tất cả functionCall trong parts (thường chỉ 1) → tạo functionResponse parts
                $responseParts = [];
                foreach ($functionCalls as $part) {
                    $fnName = $part['functionCall']['name'] ?? '';
                    $fnArgs = $part['functionCall']['args'] ?? [];

                    $result = $this->tools->call($fnName, is_array($fnArgs) ? $fnArgs : []);
                    $lastToolCalled = $fnName;

                    Log::info('Chatbot tool called', [
                        'tool' => $fnName,
                        'args' => $fnArgs,
                        'result_keys' => array_keys($result),
                    ]);

                    $responseParts[] = [
                        'functionResponse' => [
                            'name' => $fnName,
                            'response' => ['content' => $result],
                        ],
                    ];
                }

                $conversation[] = [
                    'role' => 'user',
                    'parts' => $responseParts,
                ];

                continue; // Loop tiếp để Gemini xử lý kết quả tool
            }

            // Không có functionCall → gom text từ tất cả parts
            $texts = array_map(fn ($p) => $p['text'] ?? '', $parts);
            $text = trim(implode("\n", array_filter($texts, fn ($t) => $t !== '')));

            if ($text === '') {
                $fallback = 'Xin lỗi anh/chị, em chưa hiểu ý câu hỏi. Anh/chị có thể diễn đạt lại giúp em không ạ?';
                $this->logModelReply($chatSession, $fallback, $lastToolCalled, $startTime);
                return response()->json(['reply' => $fallback]);
            }

            if ($finishReason === 'MAX_TOKENS') {
                $text .= '...';
            }

            $this->logModelReply($chatSession, $text, $lastToolCalled, $startTime);
            return response()->json(['reply' => $text]);
        }

        Log::warning('Chatbot exceeded max turns', ['max' => $maxTurns]);
        $fallback = 'Em xin lỗi, câu hỏi này em cần suy nghĩ thêm. Anh/chị có thể hỏi lại ngắn gọn hơn được không ạ?';
        $this->logModelReply($chatSession, $fallback, $lastToolCalled, $startTime);
        return response()->json(['reply' => $fallback]);
    }

    /**
     * Gọi Gemini API với retry logic cho lỗi 503 (server bận).
     * Trả về null nếu network error / timeout hoàn toàn.
     */
    private function callGeminiWithRetry(string $url, array $payload, int $maxRetries = 1): ?\Illuminate\Http\Client\Response
    {
        $lastResponse = null;

        for ($attempt = 0; $attempt <= $maxRetries; $attempt++) {
            try {
                $lastResponse = Http::timeout(15)->post($url, $payload);
            } catch (\Throwable $e) {
                Log::error('Chatbot Gemini timeout/network', [
                    'attempt' => $attempt + 1,
                    'error' => $e->getMessage(),
                ]);
                if ($attempt === $maxRetries) {
                    return null;
                }
                usleep(500_000); // 0.5s trước khi retry
                continue;
            }

            // Retry cho lỗi 503 (server overloaded)
            if ($lastResponse->status() === 503 && $attempt < $maxRetries) {
                Log::info('Chatbot retrying due to 503', ['attempt' => $attempt + 1]);
                sleep(1); // đợi 1s
                continue;
            }

            return $lastResponse;
        }

        return $lastResponse;
    }

    /**
     * Lấy hoặc tạo session hiện tại theo Laravel session (`chatbot_session_id`).
     * Trả về null nếu ghi DB fail — caller phải xử lý null.
     */
    private function openChatSession(Request $request): ?ChatbotSession
    {
        try {
            $token = session()->get('chatbot_session_id');
            if (!$token) {
                $token = (string) Str::uuid();
                session()->put('chatbot_session_id', $token);
            }

            $chatSession = ChatbotSession::firstOrCreate(
                ['session_token' => $token],
                [
                    'user_id' => auth()->id(),
                    'user_agent' => mb_substr((string) $request->userAgent(), 0, 500),
                    'ip_address' => $request->ip(),
                ]
            );

            // Nếu session tạo trước khi login mà giờ đã login → cập nhật user_id
            if (!$chatSession->user_id && auth()->id()) {
                $chatSession->update(['user_id' => auth()->id()]);
            }

            return $chatSession;
        } catch (\Throwable $e) {
            Log::warning('Chatbot session open failed', ['error' => $e->getMessage()]);
            return null;
        }
    }

    private function logMessage(?ChatbotSession $session, string $role, string $content, ?string $tool = null, ?int $responseTimeMs = null): void
    {
        if (!$session) {
            return;
        }
        try {
            ChatbotMessage::create([
                'session_id' => $session->id,
                'role' => $role,
                'content' => $content,
                'tool_used' => $tool,
                'response_time_ms' => $responseTimeMs,
                'created_at' => now(),
            ]);
        } catch (\Throwable $e) {
            Log::warning('Chatbot log message failed', ['error' => $e->getMessage()]);
        }
    }

    private function logModelReply(?ChatbotSession $session, string $text, ?string $tool, float $startTime): void
    {
        $elapsed = (int) round((microtime(true) - $startTime) * 1000);
        $this->logMessage($session, 'model', $text, $tool, $elapsed);
    }

    private function buildSystemPrompt(): string
    {
        $user = auth()->user();
        $userLine = $user
            ? "Khách đăng nhập: {$user->email}."
            : "Khách chưa đăng nhập.";

        return "Bạn là trợ lý TGDĐ (điện thoại, laptop, phụ kiện). Xưng 'em', gọi khách 'anh/chị'. Trả lời 1-3 câu, không markdown.\n"
            . $userLine . "\n\n"
            . "Chính sách: Đổi trả 7 ngày. Bảo hành 12 tháng. Thanh toán COD/VNPay/MoMo. Giao 1-7 ngày, freeship >500k. Hotline 1800.1060.\n\n"
            . "TOOLS: Khi khách hỏi giá/tồn kho/cấu hình → search_products hoặc get_product_details. Bán chạy → get_hot_products. Voucher → get_active_vouchers. Đơn hàng cá nhân → get_my_orders/get_order_details (cần login). Chính sách chung → trả lời trực tiếp, KHÔNG gọi tool. Không đoán giá.";
    }
}
