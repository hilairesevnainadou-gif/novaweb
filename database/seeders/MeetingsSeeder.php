<?php

namespace Database\Seeders;

use App\Models\Meeting;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class MeetingsSeeder extends Seeder
{
    public function run(): void
    {
        $projects = Project::query()->pluck('id')->all();
        $users = User::query()->pluck('id')->all();

        if (empty($projects) || empty($users)) {
            return;
        }

        $meetings = [
            ['title' => 'Kickoff projet', 'status' => Meeting::STATUS_COMPLETED, 'offset' => -7],
            ['title' => 'Suivi hebdomadaire', 'status' => Meeting::STATUS_SCHEDULED, 'offset' => 2],
            ['title' => 'Revue technique', 'status' => Meeting::STATUS_IN_PROGRESS, 'offset' => 0],
        ];

        foreach ($meetings as $index => $item) {
            Meeting::query()->create([
                'project_id' => $projects[array_rand($projects)],
                'title' => $item['title'],
                'description' => 'Réunion générée par seeder.',
                'meeting_date' => now()->addDays($item['offset'])->setTime(10 + $index, 0),
                'duration_minutes' => 60 + ($index * 30),
                'meeting_link' => 'https://meet.novatech.local/'.($index + 1),
                'location' => $index === 0 ? 'Salle A' : 'En ligne',
                'organizer_id' => $users[array_rand($users)],
                'attendees' => array_values(array_slice($users, 0, min(3, count($users)))),
                'minutes' => $item['status'] === Meeting::STATUS_COMPLETED ? 'Compte-rendu validé.' : null,
                'decisions' => $item['status'] === Meeting::STATUS_COMPLETED ? 'Décisions prises.' : null,
                'action_items' => ['Préparer sprint suivant', 'Valider backlog'],
                'status' => $item['status'],
                'recorded' => $index % 2 === 0,
            ]);
        }
    }
}
