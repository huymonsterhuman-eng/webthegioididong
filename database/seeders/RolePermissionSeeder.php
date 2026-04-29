<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define all permissions
        $permissions = [
            'access_admin',
            'view_reports',

            // Roles & System
            'manage_roles',
            'view_system_logs',
            'view_activity_logs',
            'view_order_logs',

            // Products & Content
            'view_products',
            'create_products',
            'edit_products',
            'delete_products',
            'manage_collections',
            'manage_banners',
            'manage_posts',

            // Categories & Brands
            'view_categories',
            'manage_categories',
            'view_brands',
            'manage_brands',

            // Inventory & Logistics
            'view_inventory',
            'manage_inventory',
            'manage_goods_receipt',
            'manage_goods_issue',
            'manage_suppliers',
            'manage_shipping',

            // Orders & Sales
            'view_orders',
            'manage_orders',
            'view_vouchers',
            'manage_vouchers',
            'view_reviews',
            'manage_reviews',

            // Users & Partners
            'view_users',
            'manage_users',
            'view_partners',
            'manage_partners',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Super Admin — full access (handled by Spatie's "super-admin" feature)
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $superAdmin->givePermissionTo(Permission::all());

        // Staff — limited admin access
        $staff = Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);
        $staff->givePermissionTo([
            'access_admin',
            'view_products',
            'edit_products',
            'view_categories',
            'view_brands',
            'view_orders',
            'manage_orders',
            'view_reviews',
            'manage_reviews',
            'view_vouchers',
            'view_partners',
        ]);
    }
}
