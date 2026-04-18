<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            CompanyInfoSeeder::class,
            ServiceSeeder::class,
            PortfolioSeeder::class,
        BlogPostSeeder::class,
            RolePermissionSeeder::class,        ]);
    }
}
