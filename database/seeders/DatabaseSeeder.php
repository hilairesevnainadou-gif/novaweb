<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            CompanyInfoSeeder::class,
            RolePermissionSeeder::class,
            ClientsSeeder::class,
            ProjectsSeeder::class,
            TasksSeeder::class,
            MeetingsSeeder::class,
            NotificationsSeeder::class,
            MaintenanceSeeder::class,
        ]);
    }
}
