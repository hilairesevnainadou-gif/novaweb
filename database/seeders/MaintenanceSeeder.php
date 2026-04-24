<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Device;
use App\Models\Intervention;
use App\Models\User;
use Illuminate\Database\Seeder;

class MaintenanceSeeder extends Seeder
{
    public function run(): void
    {
        $clientIds = Client::query()->pluck('id')->all();
        $userIds = User::query()->pluck('id')->all();

        if (empty($userIds) || empty($clientIds)) {
            return;
        }

        $devicesData = [
            [
                'reference' => 'DEV-LAP-001',
                'name' => 'Poste Direction',
                'brand' => 'Dell',
                'model' => 'Latitude 5420',
                'serial_number' => 'SN-DL-5420-001',
                'category' => Device::CATEGORY_COMPUTER,
                'status' => Device::STATUS_OPERATIONAL,
                'location' => 'Bureau Direction',
            ],
            [
                'reference' => 'DEV-PRN-002',
                'name' => 'Imprimante Compta',
                'brand' => 'HP',
                'model' => 'LaserJet Pro',
                'serial_number' => 'SN-HP-LJ-002',
                'category' => Device::CATEGORY_PRINTER,
                'status' => Device::STATUS_MAINTENANCE,
                'location' => 'Service Comptabilité',
            ],
        ];

        $deviceIds = [];

        foreach ($devicesData as $index => $data) {
            $device = Device::query()->updateOrCreate(
                ['reference' => $data['reference']],
                array_merge($data, [
                    'purchase_date' => now()->subMonths(8 + $index),
                    'warranty_end_date' => now()->addMonths(12 + $index),
                    'client_id' => $clientIds[array_rand($clientIds)],
                    'technical_specs' => ['ram' => '16GB', 'storage' => '512GB SSD'],
                    'image' => null,
                    'is_active' => true,
                    'order' => $index + 1,
                ])
            );

            $deviceIds[] = $device->id;
        }

        if (empty($deviceIds)) {
            return;
        }

        $interventions = [
            [
                'title' => 'Diagnostic lenteur système',
                'problem_type' => Intervention::PROBLEM_SOFTWARE,
                'status' => Intervention::STATUS_IN_PROGRESS,
                'priority' => Intervention::PRIORITY_HIGH,
            ],
            [
                'title' => 'Remplacement toner',
                'problem_type' => Intervention::PROBLEM_HARDWARE,
                'status' => Intervention::STATUS_COMPLETED,
                'priority' => Intervention::PRIORITY_MEDIUM,
            ],
        ];

        foreach ($interventions as $index => $item) {
            Intervention::query()->create([
                'device_id' => $deviceIds[array_rand($deviceIds)],
                'client_id' => $clientIds[array_rand($clientIds)],
                'technician_id' => $userIds[array_rand($userIds)],
                'title' => $item['title'],
                'description' => 'Intervention générée automatiquement.',
                'problem_type' => $item['problem_type'],
                'problem_description' => 'Description standard pour environnement de test.',
                'solution' => $item['status'] === Intervention::STATUS_COMPLETED ? 'Solution appliquée et validée.' : null,
                'status' => $item['status'],
                'priority' => $item['priority'],
                'evolution_level' => min(5, $index + 2),
                'estimated_cost' => 120 + ($index * 80),
                'actual_cost' => $item['status'] === Intervention::STATUS_COMPLETED ? 100 + ($index * 50) : 0,
                'start_date' => now()->subDays(3 + $index),
                'end_date' => $item['status'] === Intervention::STATUS_COMPLETED ? now()->subDay() : null,
                'scheduled_date' => now()->addDays($index + 1),
                'duration_minutes' => 90 + ($index * 30),
                'parts_used' => ['Pièce A', 'Pièce B'],
                'notes' => 'Créé par MaintenanceSeeder.',
                'attachments' => [],
                'signature' => null,
                'client_rated' => false,
                'client_rating' => null,
                'client_feedback' => null,
            ]);
        }
    }
}
