<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientsSeeder extends Seeder
{
    public function run(): void
    {
        $clients = [
            [
                'client_type' => 'company',
                'company_name' => 'Atlas Industries',
                'tax_number' => 'MF-ATLAS-001',
                'website' => 'https://atlas-industries.tn',
                'name' => 'Sami Ben Youssef',
                'email' => 'contact@atlas-industries.tn',
                'phone' => '+21620000001',
                'gender' => 'M',
                'address' => '12 Rue de l’Industrie',
                'city' => 'Tunis',
                'country' => 'Tunisie',
                'logo' => null,
                'contact_name' => 'Sami Ben Youssef',
                'contact_position' => 'Directeur IT',
                'invoice_by_email' => true,
                'billing_cycle' => 'monthly',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'client_type' => 'company',
                'company_name' => 'Blue Retail Group',
                'tax_number' => 'MF-BLUE-002',
                'website' => 'https://blue-retail.tn',
                'name' => 'Nour Elhadi',
                'email' => 'it@blue-retail.tn',
                'phone' => '+21620000002',
                'gender' => 'F',
                'address' => '45 Avenue Habib Bourguiba',
                'city' => 'Sfax',
                'country' => 'Tunisie',
                'logo' => null,
                'contact_name' => 'Nour Elhadi',
                'contact_position' => 'Responsable SI',
                'invoice_by_email' => true,
                'billing_cycle' => 'quarterly',
                'is_active' => true,
                'order' => 2,
            ],
            [
                'client_type' => 'individual',
                'company_name' => null,
                'tax_number' => null,
                'website' => null,
                'name' => 'Ahmed Trabelsi',
                'email' => 'ahmed.trabelsi@gmail.com',
                'phone' => '+21620000003',
                'gender' => 'M',
                'address' => '8 Rue de Carthage',
                'city' => 'Ariana',
                'country' => 'Tunisie',
                'logo' => null,
                'contact_name' => 'Ahmed Trabelsi',
                'contact_position' => 'Propriétaire',
                'invoice_by_email' => true,
                'billing_cycle' => 'monthly',
                'is_active' => true,
                'order' => 3,
            ],
            [
                'client_type' => 'individual',
                'company_name' => null,
                'tax_number' => null,
                'website' => null,
                'name' => 'Meriem Gharbi',
                'email' => 'meriem.gharbi@gmail.com',
                'phone' => '+21620000004',
                'gender' => 'F',
                'address' => '22 Rue Ennasr',
                'city' => 'Sousse',
                'country' => 'Tunisie',
                'logo' => null,
                'contact_name' => 'Meriem Gharbi',
                'contact_position' => 'Particulier',
                'invoice_by_email' => false,
                'billing_cycle' => 'monthly',
                'is_active' => true,
                'order' => 4,
            ],
        ];

        foreach ($clients as $client) {
            Client::query()->updateOrCreate(
                ['email' => $client['email']],
                $client
            );
        }
    }
}
