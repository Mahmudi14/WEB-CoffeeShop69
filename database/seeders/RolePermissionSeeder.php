<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'start_shift',
            'close_shift',
            'create_order',
            'verify_payment',
            'print_receipt',
            'input_shift_expense',

            'manage_menu',
            'manage_category',
            'manage_table',
            'manage_promo',
            'manage_tax',
            'manage_cashier',
            'manage_admin',

            'cancel_order',
            'view_report',
            'view_activity_log',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $cashier = Role::firstOrCreate([
            'name' => 'cashier',
            'guard_name' => 'web',
        ]);

        $admin = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $superadmin = Role::firstOrCreate([
            'name' => 'superadmin',
            'guard_name' => 'web',
        ]);

        $cashier->syncPermissions([
            'start_shift',
            'close_shift',
            'create_order',
            'verify_payment',
            'print_receipt',
            'input_shift_expense',
        ]);

        $admin->syncPermissions([
            'manage_menu',
            'manage_category',
            'manage_table',
            'manage_promo',
            'manage_tax',
            'manage_cashier',
            'cancel_order',
            'view_report',
        ]);

        $superadmin->syncPermissions(Permission::all());

        $superadminUser = User::firstOrCreate(
            ['email' => 'superadmin@69coffeeshop.test'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );

        $adminUser = User::firstOrCreate(
            ['email' => 'admin@69coffeeshop.test'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );

        $cashierUser = User::firstOrCreate(
            ['email' => 'cashier@69coffeeshop.test'],
            [
                'name' => 'Kasir',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );

        $superadminUser->syncRoles(['superadmin']);
        $adminUser->syncRoles(['admin']);
        $cashierUser->syncRoles(['cashier']);
    }
}