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

        if ($permission === 'view_inventory') {
            return $user->can('create_inventory');
        }

        // Map view_* to edit_*
        $editPermission = str_replace('view_', 'edit_', $permission);
        
        // Special case mapping
        if ($permission === 'view_reviews') {
            $editPermission = 'moderate_reviews';
        }

        return $user->can($editPermission);
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

        if ($permission === 'view_inventory') {
            return $user->can('edit_inventory');
        }

        // Map view_* to edit_*
        $editPermission = str_replace('view_', 'edit_', $permission);
        
        // Special case mapping
        if ($permission === 'view_reviews') {
            $editPermission = 'moderate_reviews';
        }

        return $user->can($editPermission);
    }

    public static function canDelete($record): bool
    {
        return Auth::check() && Auth::user()->hasRole('super-admin');
    }
}
