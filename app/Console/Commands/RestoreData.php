<?php

namespace App\Console\Commands;

use App\Usecase\Admin\SidebarMenuUsecase;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RestoreData extends Command
{
    protected $signature = 'app:restore-data';

    protected $description = 'Restore application data (users, institutions, mentors, interns, daily logs, holidays, menus) from seed file';

    public function handle(SidebarMenuUsecase $sidebarUsecase): int
    {
        $this->info('Restoring application data...');

        $sqlPath = database_path('seeders/production_data.sql');

        if (! file_exists($sqlPath)) {
            $this->error("File not found: {$sqlPath}");

            return self::FAILURE;
        }

        Schema::disableForeignKeyConstraints();

        $sql = file_get_contents($sqlPath);
        DB::unprepared($sql);

        Schema::enableForeignKeyConstraints();

        $this->info('Data restored successfully!');
        $this->line('  Users count: ' . DB::table('users')->count());
        $this->line('  Daily logs count: ' . DB::table('daily_logs')->count());
        $this->line('  Institutions count: ' . DB::table('institutions')->count());
        $this->line('  Mentors count: ' . DB::table('mentors')->count());
        $this->line('  Interns count: ' . DB::table('interns')->count());
        $this->line('  Holidays count: ' . DB::table('holidays')->count());

        $this->info('Refreshing sidebar cache...');
        $sidebarUsecase->refreshSidebarCache();

        return self::SUCCESS;
    }
}
