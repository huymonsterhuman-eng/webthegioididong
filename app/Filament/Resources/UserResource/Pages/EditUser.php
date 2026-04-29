<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected array $oldRoles = [];
    protected bool $passwordChanged = false;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->oldRoles = $this->record->roles->pluck('name')->toArray();
        $this->passwordChanged = !empty($data['password']); // password field is only filled if it's being changed
        return $data;
    }

    protected function afterSave(): void
    {
        $newRoles = $this->record->roles()->pluck('name')->toArray();
        $admin = auth()->user()->username ?? 'System';

        if ($this->passwordChanged) {
            \App\Services\ActivityLogService::log(
                'user_password_changed',
                "Admin {$admin} đã đổi mật khẩu của tài khoản {$this->record->username}.",
                'system',
                $this->record,
                []
            );
        }

        sort($this->oldRoles);
        sort($newRoles);
        
        if ($this->oldRoles !== $newRoles) {
            \App\Services\ActivityLogService::log(
                'user_roles_changed',
                "Admin {$admin} đã thay đổi vai trò của tài khoản {$this->record->username}.",
                'system',
                $this->record,
                [
                    'old_roles' => $this->oldRoles,
                    'new_roles' => $newRoles,
                ]
            );
        }

        // Log Profile Changes
        $changes = $this->record->getChanges();
        $fieldsToLog = ['full_name', 'phone', 'gender', 'birthday', 'status'];
        
        foreach ($fieldsToLog as $field) {
            if (array_key_exists($field, $changes)) {
                $oldValue = $this->record->getOriginal($field);
                $newValue = $changes[$field];
                
                $fieldLabel = match ($field) {
                    'full_name' => 'Họ tên',
                    'phone' => 'Số điện thoại',
                    'gender' => 'Giới tính',
                    'birthday' => 'Ngày sinh',
                    'status' => 'Trạng thái',
                    default => $field,
                };

                \App\Services\ActivityLogService::log(
                    'user_profile_updated',
                    "Admin {$admin} đã cập nhật trường {$fieldLabel} của tài khoản {$this->record->username}: '{$oldValue}' -> '{$newValue}'.",
                    'system',
                    $this->record,
                    [
                        'field' => $field,
                        'old' => $oldValue,
                        'new' => $newValue,
                    ]
                );
            }
        }
    }
}
