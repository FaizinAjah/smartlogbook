<?php

namespace App\Console\Commands;

use App\Usecase\Admin\SidebarMenuUsecase;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class InitSidebar extends Command
{
    protected $signature = 'app:init-sidebar';

    protected $description = 'Seed sidebar groups, menus, and access permissions, then refresh cache';

    public function handle(SidebarMenuUsecase $usecase): int
    {
        $this->info('Initializing sidebar groups, menus, and access...');

        // 1. Sidebar Group
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
        $this->line('  ✓ sidebar_menu_groups: utama');

        // 2. Sidebar Menus
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
                array_merge($m, ['created_at' => now(), 'updated_at' => now()])
            );
            $this->line("  ✓ sidebar_menus: {$m['label']} ({$m['route_name']})");
        }

        // 3. Sidebar Menu Accesses
        // Access types: 1 = Superadmin, 2 = Anggota/Intern, 3 = Pembimbing
        $accesses = [
            // Dashboard: all
            ['sidebar_menu_id' => 1, 'access_type' => 1],
            ['sidebar_menu_id' => 1, 'access_type' => 2],
            ['sidebar_menu_id' => 1, 'access_type' => 3],

            // Pengguna Aplikasi: Superadmin only
            ['sidebar_menu_id' => 2, 'access_type' => 1],

            // Log Book: all
            ['sidebar_menu_id' => 3, 'access_type' => 1],
            ['sidebar_menu_id' => 3, 'access_type' => 2],
            ['sidebar_menu_id' => 3, 'access_type' => 3],

            // Hari Libur: Superadmin only
            ['sidebar_menu_id' => 4, 'access_type' => 1],

            // Institusi: Superadmin only
            ['sidebar_menu_id' => 5, 'access_type' => 1],

            // Peserta Magang: Superadmin & Pembimbing
            ['sidebar_menu_id' => 6, 'access_type' => 1],
            ['sidebar_menu_id' => 6, 'access_type' => 3],
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
        $this->line('  ✓ sidebar_menu_accesses seeded (11 entries)');

        // 4. Refresh Cache
        $this->info('Refreshing sidebar cache...');
        Cache::flush();
        $usecase->refreshSidebarCache();

        $this->info('Done! Sidebar menus initialized and cache flushed.');

        return self::SUCCESS;
    }
}
