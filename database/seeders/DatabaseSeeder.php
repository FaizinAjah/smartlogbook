<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Super Admin User
        $adminEmail = env('SEED_SUPERADMIN_EMAIL', 'admin@admin.com');
        $adminPassword = env('SEED_SUPERADMIN_PASSWORD', 'password');
        $adminName = env('SEED_SUPERADMIN_NAME', 'Super User');

        DB::table('users')->updateOrInsert(
            ['email' => $adminEmail],
            [
                'name' => $adminName,
                'password' => Hash::make($adminPassword),
                'access_type' => 1,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // 2. Sidebar Groups
        DB::table('sidebar_menu_groups')->updateOrInsert(
            ['key' => 'utama'],
            [
                'label' => 'Utama',
                'color' => 'blue',
                'sort_order' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // 3. Sidebar Menus
        $menus = [
            [
                'id' => 1,
                'parent_id' => null,
                'label' => 'Dashboard',
                'route_name' => 'admin.dashboard',
                'icon' => '_admin._layout.icons.sidebar.dashboard',
                'group' => 'utama',
                'sort_order' => 1,
                'is_active' => 1,
            ],
            [
                'id' => 2,
                'parent_id' => null,
                'label' => 'Pengguna Aplikasi',
                'route_name' => 'admin.users.index',
                'icon' => '_admin._layout.icons.sidebar.user',
                'group' => 'utama',
                'sort_order' => 99,
                'is_active' => 1,
            ],
            [
                'id' => 3,
                'parent_id' => null,
                'label' => 'Log Book',
                'route_name' => 'admin.log_book.index',
                'icon' => '_admin._layout.icons.sidebar.log_book',
                'group' => 'utama',
                'sort_order' => 2,
                'is_active' => 1,
            ],
            [
                'id' => 4,
                'parent_id' => null,
                'label' => 'Hari Libur',
                'route_name' => 'admin.holidays.index',
                'icon' => '_admin._layout.icons.sidebar.calendar',
                'group' => 'utama',
                'sort_order' => 3,
                'is_active' => 1,
            ],
            [
                'id' => 5,
                'parent_id' => null,
                'label' => 'Institusi',
                'route_name' => 'admin.institutions.index',
                'icon' => '_admin._layout.icons.sidebar.institution',
                'group' => 'utama',
                'sort_order' => 4,
                'is_active' => 1,
            ],
            [
                'id' => 6,
                'parent_id' => null,
                'label' => 'Peserta Magang',
                'route_name' => 'admin.interns.index',
                'icon' => '_admin._layout.icons.sidebar.intern',
                'group' => 'utama',
                'sort_order' => 5,
                'is_active' => 1,
            ],
        ];

        foreach ($menus as $m) {
            DB::table('sidebar_menus')->updateOrInsert(
                ['id' => $m['id']],
                array_merge($m, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }

        // 4. Sidebar Accesses
        // access_type: 1 = Superadmin, 2 = Anggota, 3 = Pembimbing
        $accesses = [
            ['sidebar_menu_id' => 1, 'access_type' => 1], // Superadmin -> Dashboard
            ['sidebar_menu_id' => 1, 'access_type' => 2], // Anggota -> Dashboard
            ['sidebar_menu_id' => 1, 'access_type' => 3], // Pembimbing -> Dashboard
            ['sidebar_menu_id' => 2, 'access_type' => 1], // Superadmin -> Users
            ['sidebar_menu_id' => 3, 'access_type' => 1], // Superadmin -> Logbook
            ['sidebar_menu_id' => 3, 'access_type' => 2], // Anggota -> Logbook
            ['sidebar_menu_id' => 3, 'access_type' => 3], // Pembimbing -> Logbook
            ['sidebar_menu_id' => 4, 'access_type' => 1], // Superadmin -> Hari Libur
            ['sidebar_menu_id' => 5, 'access_type' => 1], // Superadmin -> Institusi
            ['sidebar_menu_id' => 6, 'access_type' => 1], // Superadmin -> Peserta Magang
            ['sidebar_menu_id' => 6, 'access_type' => 3], // Pembimbing -> Peserta Magang
        ];

        foreach ($accesses as $acc) {
            DB::table('sidebar_menu_accesses')->updateOrInsert(
                [
                    'sidebar_menu_id' => $acc['sidebar_menu_id'],
                    'access_type' => $acc['access_type'],
                ],
                [
                    'created_at' => now(),
                ]
            );
        }
    }
}
