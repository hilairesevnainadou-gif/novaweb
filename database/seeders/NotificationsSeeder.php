<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationsSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::query()->pluck('id')->all();

        if (empty($users)) {
            return;
        }

        $templates = [
            [
                'type' => 'task_assigned',
                'title' => 'Nouvelle tâche assignée',
                'message' => 'Une nouvelle tâche vous a été assignée.',
                'url' => '/admin/tasks',
            ],
            [
                'type' => 'meeting_reminder',
                'title' => 'Rappel réunion',
                'message' => 'Vous avez une réunion planifiée aujourd’hui.',
                'url' => '/admin/meetings',
            ],
            [
                'type' => 'intervention_status',
                'title' => 'Intervention mise à jour',
                'message' => 'Le statut d’une intervention a changé.',
                'url' => '/admin/maintenance/interventions',
            ],
        ];

        foreach ($users as $userId) {
            foreach ($templates as $index => $template) {
                Notification::query()->create([
                    'user_id' => $userId,
                    'type' => $template['type'],
                    'title' => $template['title'],
                    'message' => $template['message'],
                    'data' => ['seed' => true, 'index' => $index],
                    'is_read' => $index === 2,
                    'read_at' => $index === 2 ? now()->subDay() : null,
                    'url' => $template['url'],
                ]);
            }
        }
    }
}
