<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProjectsSeeder extends Seeder
{
    public function run(): void
    {
        $managerIds = User::query()->pluck('id')->all();
        $clientIds = Client::query()->pluck('id')->all();

        if (empty($managerIds)) {
            return;
        }

        $projects = [
            [
                'name' => 'Plateforme Support IT',
                'description' => 'Application de gestion des tickets et interventions.',
                'type' => Project::TYPE_SOFTWARE,
                'status' => Project::STATUS_IN_PROGRESS,
                'priority' => Project::PRIORITY_HIGH,
                'progress_percentage' => 45,
                'estimated_hours' => 320,
                'actual_hours' => 140,
                'budget' => 25000,
                'technologies' => ['Laravel', 'MySQL', 'Bootstrap'],
            ],
            [
                'name' => 'Application Mobile Techniciens',
                'description' => 'Application mobile pour suivi des interventions terrain.',
                'type' => Project::TYPE_MOBILE,
                'status' => Project::STATUS_PLANNING,
                'priority' => Project::PRIORITY_MEDIUM,
                'progress_percentage' => 10,
                'estimated_hours' => 220,
                'actual_hours' => 20,
                'budget' => 18000,
                'technologies' => ['Flutter', 'Laravel API'],
            ],
            [
                'name' => 'Portail Client NovaTech',
                'description' => 'Espace client pour factures, tickets et notifications.',
                'type' => Project::TYPE_WEB,
                'status' => Project::STATUS_REVIEW,
                'priority' => Project::PRIORITY_CRITICAL,
                'progress_percentage' => 82,
                'estimated_hours' => 280,
                'actual_hours' => 240,
                'budget' => 30000,
                'technologies' => ['Laravel', 'Livewire', 'Tailwind'],
            ],
        ];

        foreach ($projects as $index => $data) {
            $name = $data['name'];

            Project::query()->updateOrCreate(
                ['slug' => Str::slug($name)],
                array_merge($data, [
                    'client_id' => ! empty($clientIds) ? $clientIds[array_rand($clientIds)] : null,
                    'project_manager_id' => $managerIds[array_rand($managerIds)],
                    'start_date' => now()->subDays(30 + ($index * 10)),
                    'end_date' => now()->addDays(45 + ($index * 20)),
                    'repository_url' => 'https://github.com/novatech/'.Str::slug($name),
                    'production_url' => 'https://'.Str::slug($name).'.novatech.local',
                    'staging_url' => 'https://staging-'.Str::slug($name).'.novatech.local',
                    'attachments' => [],
                    'is_active' => true,
                ])
            );
        }
    }
}
