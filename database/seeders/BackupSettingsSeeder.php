<?php
// database/seeders/BackupSettingsSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BackupSetting;

class BackupSettingsSeeder extends Seeder
{
    public function run()
    {
        BackupSetting::create([
            'backup_email' => config('mail.from.address'),
            'backup_frequency' => 'daily',
            'backup_time' => '02:00',
            'auto_clean_days' => 30,
            'backup_type' => 'full',
            'backup_path' => storage_path('app/backups'),
            'is_active' => true,
            'last_backup_at' => null
        ]);
    }
}
