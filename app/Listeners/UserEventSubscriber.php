<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Events\Dispatcher;
use App\Services\ActivityLogService;

class UserEventSubscriber
{
    /**
     * Handle user login events.
     */
    public function handleUserLogin(Login $event): void
    {
        /** @var \App\Models\User|null $user */
        $user = $event->user;
        if ($user instanceof \App\Models\User) {
            ActivityLogService::log(
                'user_login',
                "Tài khoản {$user->username} đã đăng nhập hệ thống.",
                'system',
                $user,
                ['ip_address' => request()->ip(), 'user_agent' => request()->userAgent()]
            );
        }
    }

    /**
     * Handle user logout events.
     */
    public function handleUserLogout(Logout $event): void
    {
        /** @var \App\Models\User|null $user */
        $user = $event->user;
        if ($user instanceof \App\Models\User) {
            ActivityLogService::log(
                'user_logout',
                "Tài khoản {$user->username} đã đăng xuất.",
                'system',
                $user,
                ['ip_address' => request()->ip()]
            );
        }
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            Login::class,
            [UserEventSubscriber::class, 'handleUserLogin']
        );

        $events->listen(
            Logout::class,
            [UserEventSubscriber::class, 'handleUserLogout']
        );
    }
}
