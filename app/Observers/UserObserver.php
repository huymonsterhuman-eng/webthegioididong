<?php

namespace App\Observers;

use App\Models\User;

/**
 * Observer đồng bộ User.status với trạng thái xác thực email.
 *
 * Quy tắc:
 *   - User mới tạo CHƯA verify email → status = 'unverified'.
 *   - Khi email_verified_at được set lần đầu (user click link verify) →
 *     status tự chuyển từ 'unverified' sang 'active' (giữ nguyên nếu đã 'banned').
 *   - Admin set status = 'banned' thủ công không bị Observer ghi đè.
 */
class UserObserver
{
    /**
     * Trước khi tạo: nếu user chưa verify email và chưa có status rõ ràng →
     * mặc định 'unverified'. Nếu admin tạo user và đã set status thì giữ nguyên.
     */
    public function creating(User $user): void
    {
        if (empty($user->status)) {
            $user->status = $user->email_verified_at ? 'active' : 'unverified';
        }
    }

    /**
     * Khi user save (kể cả lúc click link verify email):
     * nếu email_verified_at vừa được set từ null → chuyển status sang 'active'
     * (trừ khi đang 'banned').
     */
    public function updating(User $user): void
    {
        // email_verified_at vừa được điền (từ null → có giá trị)
        if (
            $user->isDirty('email_verified_at') &&
            $user->email_verified_at !== null &&
            $user->getOriginal('email_verified_at') === null &&
            $user->status !== 'banned'
        ) {
            $user->status = 'active';
        }
    }
}
