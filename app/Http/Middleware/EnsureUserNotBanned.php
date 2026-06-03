<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware kick user bị banned khỏi mọi request.
 *
 * Hoạt động: nếu user đang login và có status = 'banned' → logout ngay,
 * invalidate session, redirect về trang login kèm thông báo.
 *
 * Bảo vệ trường hợp: user đang có session sống thì admin mới banned →
 * không cần đợi user logout/login lại, ngay request tiếp theo họ bị đẩy ra.
 */
class EnsureUserNotBanned
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Safety net: super-admin không bao giờ bị kick (tránh trường hợp lock-out
        // toàn hệ thống do dữ liệu lỗi hoặc admin lỡ tay self-ban).
        if ($user && $user->status === 'banned' && ! $user->hasRole('super-admin')) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Nếu là request AJAX (Livewire/Filament) → trả 403
            if ($request->expectsJson()) {
                abort(403, 'Tài khoản của bạn đã bị khoá.');
            }

            return redirect()->route('login')
                ->with('error', 'Tài khoản của bạn đã bị khoá. Vui lòng liên hệ admin.');
        }

        return $next($request);
    }
}
