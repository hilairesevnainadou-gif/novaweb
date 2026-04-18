<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Commande pour nettoyer les anciens contacts
Artisan::command('nova:clean-contacts', function () {
    $deleted = App\Models\Contact::where('created_at', '<', now()->subMonths(6))->delete();
    $this->info("{$deleted} anciens messages supprimés.");
})->purpose('Supprimer les messages de contact vieux de plus de 6 mois');

// Commande pour générer le sitemap
Artisan::command('nova:generate-sitemap', function () {
    $this->call('sitemap:generate');
})->purpose('Générer le sitemap du site');

// Commande pour vérifier les permissions
Artisan::command('nova:check-permissions', function () {
    $users = App\Models\User::with('roles', 'permissions')->get();

    $this->table(
        ['ID', 'Nom', 'Email', 'Rôles', 'Permissions'],
        $users->map(fn($user) => [
            $user->id,
            $user->name,
            $user->email,
            $user->roles->pluck('name')->implode(', '),
            $user->permissions->pluck('name')->implode(', ')
        ])
    );
})->purpose('Vérifier les permissions des utilisateurs');

/*
|--------------------------------------------------------------------------
| Tâches planifiées
|--------------------------------------------------------------------------
*/

Schedule::command('nova:clean-contacts')->weekly();
Schedule::command('nova:generate-sitemap')->daily();
Schedule::command('backup:clean')->daily();
Schedule::command('backup:run')->daily()->at('02:00');
