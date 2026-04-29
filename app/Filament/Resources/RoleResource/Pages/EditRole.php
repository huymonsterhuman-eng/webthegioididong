<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    protected array $oldPermissions = [];

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }

    /**
     * This is the CORRECT Filament hook for pre-populating form data before display.
     * It runs ONCE when the page loads and is NOT affected by live() re-renders.
     * This replaces the broken afterStateHydrated() approach on each CheckboxList.
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $record = $this->record;
        $recordPerms = $record->permissions->pluck('name')->toArray();
        $groups = RoleResource::getPermissionGroups();

        foreach ($groups as $groupName => $perms) {
            $fieldName = 'permissions_' . str_replace([' ', '&'], ['_', ''], $groupName);
            $data[$fieldName] = array_values(array_intersect($recordPerms, $perms));
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->oldPermissions = $this->record->permissions->pluck('name')->toArray();
        return $data;
    }

    protected function afterSave(): void
    {
        $newPermissions = $this->record->permissions()->pluck('name')->toArray();
        $admin = auth()->user()->username ?? 'System';

        sort($this->oldPermissions);
        sort($newPermissions);
        
        if ($this->oldPermissions !== $newPermissions) {
            \App\Services\ActivityLogService::log(
                'role_permissions_changed',
                "Admin {$admin} đã thay đổi quyền hạn của vai trò '{$this->record->name}'.",
                'system',
                $this->record,
                [
                    'old_permissions' => $this->oldPermissions,
                    'new_permissions' => $newPermissions,
                ]
            );
        }
    }
}
