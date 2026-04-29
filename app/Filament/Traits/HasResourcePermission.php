<?php

namespace App\Filament\Traits;

use Illuminate\Support\Facades\Auth;

/**
 * Mixin for Filament Resources to guard navigation and access via Spatie roles/permissions.
 *
 * Each Resource must declare:
 *   protected static string $requiredPermission = 'some_permission_slug';
 */
trait HasResourcePermission
{
    public static function canViewAny(): bool
    {
        if (!Auth::check()) return false;
        $user = Auth::user();

        if ($user->hasRole('super-admin')) return true;

        $permission = static::$requiredPermission ?? '';
        if (empty($permission)) return false;

        return $user->can($permission);
    }

    public static function canCreate(): bool
    {
        if (!Auth::check()) return false;
        $user = Auth::user();

        if ($user->hasRole('super-admin')) return true;

        $permission = static::$requiredPermission ?? '';
        if (empty($permission)) return false;

        // If the base required permission is manage_*, they can create
        if (str_starts_with($permission, 'manage_')) {
            return $user->can($permission);
        }

        // Special case for inventory (always use manage_inventory)
        if ($permission === 'view_inventory') {
            return $user->can('manage_inventory');
        }

        // Special case for reviews (map view_* to manage_*)
        if ($permission === 'view_reviews') {
            return $user->can('manage_reviews');
        }

        // Map view_* to create_*
        if (str_starts_with($permission, 'view_')) {
            $createPermission = str_replace('view_', 'create_', $permission);
            $managePermission = str_replace('view_', 'manage_', $permission);
            
            // Check for explicit create OR broad manage permission
            return $user->can($createPermission) || $user->can($managePermission);
        }

        return $user->can($permission);
    }

    public static function canEdit($record): bool
    {
        if (!Auth::check()) return false;
        $user = Auth::user();

        if ($user->hasRole('super-admin')) return true;

        $permission = static::$requiredPermission ?? '';
        if (empty($permission)) return false;

        // If the base required permission is manage_*, they can edit
        if (str_starts_with($permission, 'manage_')) {
            return $user->can($permission);
        }

        // Special case for inventory (always use manage_inventory)
        if ($permission === 'view_inventory') {
            return $user->can('manage_inventory');
        }

        // Special case for reviews (map view_* to manage_*)
        if ($permission === 'view_reviews') {
            return $user->can('manage_reviews');
        }

        // Map view_* to edit_*
        if (str_starts_with($permission, 'view_')) {
            $editPermission = str_replace('view_', 'edit_', $permission);
            $managePermission = str_replace('view_', 'manage_', $permission);
            
            // Check for explicit edit OR broad manage permission
            return $user->can($editPermission) || $user->can($managePermission);
        }

        return $user->can($permission);
    }

    public static function canDelete($record): bool
    {
        if (!Auth::check()) return false;
        $user = Auth::user();

        if ($user->hasRole('super-admin')) return true;

        $permission = static::$requiredPermission ?? '';
        if (empty($permission)) return false;

        // If the base required permission is manage_*, they can delete
        if (str_starts_with($permission, 'manage_')) {
            return $user->can($permission);
        }

        // Map view_* to delete_*
        if (str_starts_with($permission, 'view_')) {
            $deletePermission = str_replace('view_', 'delete_', $permission);
            $managePermission = str_replace('view_', 'manage_', $permission);
            
            return $user->can($deletePermission) || $user->can($managePermission);
        }

        return $user->can($permission);
    }
}
