<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Http\Controllers\Admin\BackupController;

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

// Commande pour exécuter le backup manuellement
Artisan::command('nova:backup', function () {
    $controller = new BackupController();
    $controller->runAutoBackup();
    $this->info('Backup exécuté avec succès');
})->purpose('Exécuter un backup manuellement');

// Commande pour nettoyer les anciens backups
Artisan::command('nova:backup-clean', function () {
    $settings = App\Models\BackupSetting::first();
    $days = $settings ? $settings->auto_clean_days : 30;

    $backupDir = storage_path('app/backups');
    if (file_exists($backupDir)) {
        $files = File::files($backupDir);
        $now = time();
        $deleted = 0;

        foreach ($files as $file) {
            $fileAge = $now - $file->getMTime();
            $daysOld = $fileAge / (60 * 60 * 24);

            if ($daysOld > $days) {
                File::delete($file->getRealPath());
                $deleted++;
            }
        }

        $this->info("{$deleted} anciens backups supprimés.");
    } else {
        $this->info("Aucun dossier de backup trouvé.");
    }
})->purpose('Nettoyer les anciens backups');

/*
|--------------------------------------------------------------------------
| Tâches planifiées
|--------------------------------------------------------------------------
*/
// Dans routes/console.php, ajoutez :
Schedule::call(function () {
    $controller = new App\Http\Controllers\Admin\BillingController();
    $controller->checkAndSendReminders();
})->dailyAt('09:00')->name('billing-check-reminders');
Schedule::command('nova:clean-contacts')->weekly();
Schedule::command('nova:generate-sitemap')->daily();
Schedule::command('backup:clean')->daily();
Schedule::command('backup:run')->daily()->at('02:00');

// Nouvelle tâche pour le backup automatique via Laravel
Schedule::call(function () {
    $controller = new BackupController();
    $controller->runAutoBackup();
})->daily()->at('02:00')->name('nova:auto-backup');
