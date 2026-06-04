<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),

            Actions\Action::make('change_password')
                ->label('Đổi mật khẩu')
                ->icon('heroicon-o-key')
                ->color('warning')
                ->modalHeading(fn () => "Đổi mật khẩu: {$this->record->username}")
                ->form([
                    Forms\Components\TextInput::make('password')
                        ->label('Mật khẩu mới')
                        ->password()
                        ->required()
                        ->minLength(8)
                        ->maxLength(255),
                    Forms\Components\TextInput::make('password_confirmation')
                        ->label('Xác nhận mật khẩu')
                        ->password()
                        ->required()
                        ->same('password'),
                ])
                ->action(function (array $data): void {
                    $this->record->update(['password' => \Illuminate\Support\Facades\Hash::make($data['password'])]);
                    $admin = auth()->user()->username ?? 'System';
                    \App\Services\ActivityLogService::log(
                        'user_password_changed',
                        "Admin {$admin} đã đổi mật khẩu của tài khoản {$this->record->username}.",
                        'system',
                        $this->record,
                        []
                    );
                    \Filament\Notifications\Notification::make()
                        ->success()
                        ->title('Đã đổi mật khẩu thành công')
                        ->send();
                })
                ->visible(fn () => auth()->user()->can('manage_users')),
        ];
    }
}
