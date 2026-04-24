<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TasksSeeder extends Seeder
{
    public function run(): void
    {
        $projects = Project::query()->get();
        $users = User::query()->pluck('id')->all();

        if ($projects->isEmpty() || empty($users)) {
            return;
        }

        $rows = [
            ['title' => 'Analyse des besoins', 'status' => Task::STATUS_TODO, 'priority' => Task::PRIORITY_HIGH, 'task_type' => Task::TYPE_RESEARCH],
            ['title' => 'Conception de la base de données', 'status' => Task::STATUS_IN_PROGRESS, 'priority' => Task::PRIORITY_MEDIUM, 'task_type' => Task::TYPE_TASK],
            ['title' => 'Développement module Auth', 'status' => Task::STATUS_REVIEW, 'priority' => Task::PRIORITY_URGENT, 'task_type' => Task::TYPE_FEATURE],
            ['title' => 'Correction bug export PDF', 'status' => Task::STATUS_APPROVED, 'priority' => Task::PRIORITY_HIGH, 'task_type' => Task::TYPE_BUG],
            ['title' => 'Documentation utilisateur', 'status' => Task::STATUS_COMPLETED, 'priority' => Task::PRIORITY_LOW, 'task_type' => Task::TYPE_DOCUMENTATION],
        ];

        foreach ($projects as $project) {
            foreach ($rows as $index => $row) {
                Task::query()->updateOrCreate(
                    [
                        'project_id' => $project->id,
                        'title' => $row['title'],
                    ],
                    [
                        'description' => 'Tâche générée automatiquement pour jeux de données de démonstration.',
                        'assigned_to' => $users[array_rand($users)],
                        'created_by' => $users[array_rand($users)],
                        'status' => $row['status'],
                        'priority' => $row['priority'],
                        'task_type' => $row['task_type'],
                        'estimated_hours' => 4 + ($index * 2),
                        'actual_hours' => $index >= 3 ? 3 + $index : 0,
                        'start_date' => now()->subDays(8 - $index),
                        'due_date' => now()->addDays(5 + $index),
                        'is_approved' => in_array($row['status'], [Task::STATUS_APPROVED, Task::STATUS_COMPLETED], true),
                        'attachments' => [],
                        'order' => $index + 1,
                    ]
                );
            }
        }
    }
}
