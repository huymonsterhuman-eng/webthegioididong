{{-- AI Chatbot Widget (Google Gemini) --}}
<div x-data="tgddChatbot()" x-cloak class="fixed bottom-6 right-6 z-[9999] font-sans">
    {{-- Floating trigger button --}}
    <button
        x-show="!open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100"
        @click="toggle()"
        class="w-14 h-14 rounded-full shadow-lg flex items-center justify-center text-brand-dark hover:shadow-xl transition-transform hover:scale-105"
        style="background-color: #fed700;"
        aria-label="Mở chat hỗ trợ">
        <i class="fa-solid fa-comments text-2xl"></i>
        <span x-show="unreadHint"
              class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full border-2 border-white"></span>
    </button>

    {{-- Chat panel --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-4"
        class="w-80 sm:w-96 h-[500px] max-h-[80vh] bg-white rounded-lg shadow-2xl flex flex-col overflow-hidden border border-gray-200">

        {{-- Header --}}
        <div class="flex items-center justify-between px-4 py-3 text-brand-dark" style="background-color: #fed700;">
            <div class="flex items-center gap-2">
                <div class="w-9 h-9 rounded-full bg-white flex items-center justify-center">
                    <i class="fa-solid fa-headset text-brand-dark"></i>
                </div>
                <div>
                    <div class="font-bold text-sm">Trợ lý TGDĐ</div>
                    <div class="text-xs opacity-75 flex items-center gap-1">
                        <span class="w-2 h-2 bg-green-500 rounded-full inline-block"></span>
                        Đang trực tuyến
                    </div>
                </div>
            </div>
            <div class="flex gap-1">
                <button @click="clearChat()"
                        class="w-8 h-8 rounded hover:bg-black/10 flex items-center justify-center transition"
                        title="Xoá cuộc trò chuyện">
                    <i class="fa-solid fa-rotate-right text-sm"></i>
                </button>
                <button @click="toggle()"
                        class="w-8 h-8 rounded hover:bg-black/10 flex items-center justify-center transition"
                        title="Đóng">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>

        {{-- Messages --}}
        <div x-ref="scrollArea" class="flex-1 overflow-y-auto p-3 space-y-3 bg-gray-50">
            {{-- Welcome message --}}
            <template x-if="messages.length === 0">
                <div class="flex flex-col gap-3">
                    <div class="flex items-start gap-2">
                        <div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0"
                             style="background-color: #fed700;">
                            <i class="fa-solid fa-robot text-xs text-brand-dark"></i>
                        </div>
                        <div class="bg-white rounded-lg px-3 py-2 text-sm shadow-sm max-w-[75%]">
                            Xin chào anh/chị! Em là trợ lý của Thế Giới Di Động. Em có thể hỗ trợ anh/chị điều gì ạ?
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2 pl-9">
                        <template x-for="q in suggestedQuestions" :key="q">
                            <button @click="sendMessage(q)"
                                    class="text-xs px-3 py-1.5 rounded-full bg-white border border-gray-300 hover:bg-yellow-50 hover:border-yellow-400 transition"
                                    x-text="q"></button>
                        </template>
                    </div>
                </div>
            </template>

            {{-- Message list --}}
            <template x-for="(msg, idx) in messages" :key="idx">
                <div class="flex items-start gap-2" :class="msg.role === 'user' ? 'flex-row-reverse' : ''">
                    <div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0"
                         :class="msg.role === 'user' ? 'bg-gray-300' : ''"
                         :style="msg.role === 'model' ? 'background-color: #fed700;' : ''">
                        <i class="fa-solid text-xs"
                           :class="msg.role === 'user' ? 'fa-user text-gray-700' : 'fa-robot text-brand-dark'"></i>
                    </div>
                    <div class="rounded-lg px-3 py-2 text-sm shadow-sm max-w-[75%] whitespace-pre-wrap"
                         :class="msg.role === 'user' ? 'text-white' : 'bg-white'"
                         :style="msg.role === 'user' ? 'background-color: #288ad6;' : ''"
                         x-text="msg.text"></div>
                </div>
            </template>

            {{-- Typing indicator --}}
            <div x-show="sending" class="flex items-start gap-2">
                <div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0"
                     style="background-color: #fed700;">
                    <i class="fa-solid fa-robot text-xs text-brand-dark"></i>
                </div>
                <div class="bg-white rounded-lg px-3 py-2 text-sm shadow-sm">
                    <div class="flex gap-1">
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0ms;"></div>
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 150ms;"></div>
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 300ms;"></div>
                    </div>
                </div>
            </div>

            {{-- Error banner --}}
            <div x-show="errorMsg"
                 class="bg-red-50 border border-red-200 text-red-700 text-xs px-3 py-2 rounded"
                 x-text="errorMsg"></div>
        </div>

        {{-- Input --}}
        <div class="border-t border-gray-200 bg-white p-2">
            <form @submit.prevent="sendMessage(input)" class="flex items-end gap-2">
                <textarea
                    x-model="input"
                    @keydown.enter.prevent="if (!$event.shiftKey) { sendMessage(input); }"
                    :disabled="sending"
                    rows="1"
                    placeholder="Nhập câu hỏi..."
                    class="flex-1 resize-none border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-300 focus:border-yellow-400 disabled:bg-gray-100"></textarea>
                <button type="submit"
                        :disabled="sending || !input.trim()"
                        class="w-10 h-10 rounded-lg flex items-center justify-center text-brand-dark disabled:opacity-50 disabled:cursor-not-allowed transition"
                        style="background-color: #fed700;">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </form>
            <div class="text-[10px] text-gray-400 text-center mt-1">
                Trợ lý ảo có thể trả lời chưa chính xác. Hotline: <strong>1800.1060</strong>
            </div>
        </div>
    </div>
</div>

<script>
    function tgddChatbot() {
        return {
            open: false,
            unreadHint: true,
            input: '',
            sending: false,
            errorMsg: '',
            messages: [],
            suggestedQuestions: [
                'Chính sách đổi trả?',
                'Có hỗ trợ trả góp không?',
                'Thời gian giao hàng?',
                'Có COD không?',
            ],

            init() {
                // Chat không persist — mỗi lần refresh trang hoặc đổi user
                // (login/logout gây reload) đều reset về trạng thái ban đầu.
            },

            toggle() {
                this.open = !this.open;
                if (this.open) {
                    this.unreadHint = false;
                    this.$nextTick(() => this.scrollToBottom());
                }
            },

            clearChat() {
                if (this.messages.length === 0) return;
                if (!confirm('Xoá toàn bộ cuộc trò chuyện?')) return;
                this.messages = [];
                this.errorMsg = '';
            },

            scrollToBottom() {
                const el = this.$refs.scrollArea;
                if (el) el.scrollTop = el.scrollHeight;
            },

            async sendMessage(text) {
                const trimmed = (text || '').trim();
                if (!trimmed || this.sending) return;

                this.errorMsg = '';
                this.messages.push({ role: 'user', text: trimmed });
                this.input = '';
                this.$nextTick(() => this.scrollToBottom());

                this.sending = true;

                try {
                    // Send last 10 turns as history (excluding the just-added message)
                    const history = this.messages.slice(0, -1).slice(-10);

                    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                    const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

                    const response = await fetch('{{ route('chatbot.message') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({
                            message: trimmed,
                            history: history,
                        }),
                    });

                    if (response.status === 429) {
                        this.errorMsg = 'Anh/chị nhắn hơi nhanh, em xin phép nghỉ 1 chút. Vui lòng thử lại sau ít giây.';
                        return;
                    }

                    const data = await response.json();

                    if (!response.ok) {
                        this.errorMsg = data.error || 'Có lỗi xảy ra. Vui lòng thử lại.';
                        return;
                    }

                    this.messages.push({ role: 'model', text: data.reply });
                } catch (err) {
                    this.errorMsg = 'Không kết nối được đến máy chủ chatbot. Kiểm tra mạng và thử lại.';
                } finally {
                    this.sending = false;
                    this.$nextTick(() => this.scrollToBottom());
                }
            },
        };
    }
</script>

<style>
    [x-cloak] { display: none !important; }
</style>
