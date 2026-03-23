<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ------- Define all permissions -------
        $permissions = [
            // Admin access
            'access_admin',

            // Dashboard
            'view_dashboard',

            // Posts / Content
            'manage_posts',

            // Orders
            'view_orders',
            'edit_orders',
            'confirm_orders',

            // Products
            'view_products',
            'edit_products',
            'manage_brands',
            'manage_categories',

            // Users
            'view_users',
            'edit_users',

            // Reviews
            'view_reviews',
            'moderate_reviews',

            // Inventory
            'view_inventory',
            'create_inventory',
            'edit_inventory',

            // Banners
            'manage_banners',

            // Vouchers
            'manage_vouchers',

            // Shipping Providers
            'manage_shipping_providers',

            // Suppliers
            'manage_suppliers',

            // Roles (permission management itself)
            'manage_roles',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // ------- Create super-admin role (all permissions) -------
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions(Permission::all());

        // Assign super-admin to the first admin user (role = 'admin')
        $admin = User::where('role', 'admin')->first() ?? User::first();
        if ($admin && !$admin->hasRole('super-admin')) {
            $admin->assignRole($superAdmin);
        }

        $this->command->info('Roles and permissions seeded successfully!');
        $this->command->info('Super-admin assigned to: ' . ($admin?->username ?? 'N/A'));
    }
}
