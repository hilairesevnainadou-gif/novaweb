<?php
// app/Http/Controllers/Admin/BackupController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BackupLog;
use App\Models\BackupSetting;
use App\Models\Notification;
use App\Models\User;
use App\Models\CompanyInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Carbon\Carbon;

class BackupController extends Controller
{
    /**
     * Afficher la page de gestion des backups
     */
    public function index()
    {
        $backups = $this->getBackupFiles();
        $settings = BackupSetting::first();

        // Si aucun paramètre n'existe, créer des paramètres par défaut
        if (!$settings) {
            $settings = BackupSetting::create([
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

        $logs = BackupLog::latest()->take(10)->get();
        $stats = $this->getBackupStats();

        return view('admin.backup.index', compact('backups', 'settings', 'logs', 'stats'));
    }

    /**
     * Créer un nouveau backup manuel
     */
    public function create(Request $request)
    {
        try {
            $type = $request->get('type', 'full');

            // Créer le backup
            $backupFile = $this->createBackup($type);

            if (!$backupFile) {
                throw new \Exception('Erreur lors de la création du backup');
            }

            $fileSize = File::exists($backupFile) ? File::size($backupFile) : 0;

            // Enregistrer dans les logs
            BackupLog::create([
                'filename' => basename($backupFile),
                'type' => 'manual',
                'status' => 'success',
                'size' => $this->formatSize($fileSize),
                'created_at' => now()
            ]);

            // Mettre à jour la date du dernier backup
            $settings = BackupSetting::first();
            if ($settings) {
                $settings->update(['last_backup_at' => now()]);
            }

            // Notifier les admins
            $this->notifyAdmins(
                'backup',
                'Sauvegarde manuelle créée',
                "Une sauvegarde manuelle a été créée avec succès. Fichier: " . basename($backupFile),
                route('admin.backup.index')
            );

            // Envoyer l'email
            $this->sendBackupEmail($backupFile, 'manual');

            return redirect()->route('admin.backup.index')
                ->with('success', 'Backup créé avec succès. Un email a été envoyé.');

        } catch (\Exception $e) {
            // Enregistrer l'erreur dans les logs
            BackupLog::create([
                'filename' => 'backup_failed_' . date('Y-m-d_H-i-s'),
                'type' => 'manual',
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'created_at' => now()
            ]);

            Log::error('Erreur lors de la création du backup: ' . $e->getMessage());
            return redirect()->route('admin.backup.index')
                ->with('error', 'Erreur lors de la création du backup: ' . $e->getMessage());
        }
    }

    /**
     * Télécharger un fichier de backup
     */
    public function download($filename)
    {
        $backupPath = storage_path('app/backups/' . $filename);

        if (!File::exists($backupPath)) {
            return redirect()->route('admin.backup.index')
                ->with('error', 'Fichier de backup introuvable');
        }

        return response()->download($backupPath, $filename, [
            'Content-Type' => 'application/zip',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
        ]);
    }

    /**
     * Supprimer un fichier de backup
     */
    public function destroy($filename)
    {
        try {
            $backupPath = storage_path('app/backups/' . $filename);

            if (File::exists($backupPath)) {
                File::delete($backupPath);

                $this->notifyAdmins(
                    'backup',
                    'Sauvegarde supprimée',
                    "Le fichier de sauvegarde {$filename} a été supprimé.",
                    route('admin.backup.index')
                );

                return redirect()->route('admin.backup.index')
                    ->with('success', 'Backup supprimé avec succès');
            }

            return redirect()->route('admin.backup.index')
                ->with('error', 'Fichier de backup introuvable');

        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du backup: ' . $e->getMessage());
            return redirect()->route('admin.backup.index')
                ->with('error', 'Erreur lors de la suppression du backup');
        }
    }

    /**
     * Restaurer un backup
     */
    public function restore($filename)
    {
        try {
            $backupPath = storage_path('app/backups/' . $filename);

            if (!File::exists($backupPath)) {
                return redirect()->route('admin.backup.index')
                    ->with('error', 'Fichier de backup introuvable');
            }

            // Logique de restauration
            $this->restoreBackup($backupPath);

            $this->notifyAdmins(
                'backup',
                'Restauration de sauvegarde',
                "Une restauration a été effectuée à partir du fichier {$filename}.",
                route('admin.backup.index')
            );

            return redirect()->route('admin.backup.index')
                ->with('success', 'Restauration effectuée avec succès.');

        } catch (\Exception $e) {
            Log::error('Erreur lors de la restauration: ' . $e->getMessage());
            return redirect()->route('admin.backup.index')
                ->with('error', 'Erreur lors de la restauration: ' . $e->getMessage());
        }
    }

    /**
     * Mettre à jour les paramètres de backup
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'backup_email' => 'nullable|email',
            'backup_frequency' => 'required|in:daily,weekly,monthly',
            'backup_time' => 'required|string',
            'auto_clean_days' => 'required|integer|min:1|max:90',
            'backup_type' => 'required|in:full,database,files'
        ]);

        $settings = BackupSetting::first();

        if ($settings) {
            $settings->update([
                'backup_email' => $request->backup_email,
                'backup_frequency' => $request->backup_frequency,
                'backup_time' => $request->backup_time,
                'auto_clean_days' => $request->auto_clean_days,
                'backup_type' => $request->backup_type
            ]);
        } else {
            BackupSetting::create([
                'backup_email' => $request->backup_email,
                'backup_frequency' => $request->backup_frequency,
                'backup_time' => $request->backup_time,
                'auto_clean_days' => $request->auto_clean_days,
                'backup_type' => $request->backup_type,
                'backup_path' => storage_path('app/backups'),
                'is_active' => true,
                'last_backup_at' => null
            ]);
        }

        $this->notifyAdmins(
            'backup',
            'Paramètres de sauvegarde modifiés',
            "Les paramètres de sauvegarde ont été mis à jour par " . auth()->user()->name,
            route('admin.backup.index')
        );

        return redirect()->route('admin.backup.index')
            ->with('success', 'Paramètres de backup mis à jour avec succès');
    }

    /**
     * Exécuter le backup automatique (appelé par le scheduler)
     */
    public function runAutoBackup()
    {
        try {
            $settings = BackupSetting::first();

            if (!$settings || !$settings->is_active) {
                Log::info('Backup automatique désactivé');
                return;
            }

            $type = $settings->backup_type;

            $backupFile = $this->createBackup($type);

            if ($backupFile && File::exists($backupFile)) {
                // Enregistrer dans les logs
                BackupLog::create([
                    'filename' => basename($backupFile),
                    'type' => 'auto',
                    'status' => 'success',
                    'size' => $this->formatSize(File::size($backupFile)),
                    'created_at' => now()
                ]);

                // Mettre à jour la date du dernier backup
                $settings->update(['last_backup_at' => now()]);

                // Notifier les admins
                $this->notifyAdmins(
                    'backup',
                    'Sauvegarde automatique effectuée',
                    "Une sauvegarde automatique a été créée avec succès. Fichier: " . basename($backupFile),
                    route('admin.backup.index')
                );

                // Envoyer l'email
                $this->sendBackupEmail($backupFile, 'auto');

                // Nettoyer les anciens backups
                $this->cleanOldBackups($settings->auto_clean_days);
            }

            Log::info('Backup automatique exécuté avec succès');

        } catch (\Exception $e) {
            // Enregistrer l'erreur
            BackupLog::create([
                'filename' => 'backup_failed_' . date('Y-m-d_H-i-s'),
                'type' => 'auto',
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'created_at' => now()
            ]);

            Log::error('Erreur lors du backup automatique: ' . $e->getMessage());

            // Notifier les admins de l'erreur
            $this->notifyAdmins(
                'backup',
                'Erreur lors de la sauvegarde automatique',
                "Une erreur est survenue lors de la sauvegarde automatique: " . $e->getMessage(),
                route('admin.backup.index')
            );
        }
    }

    /**
     * Supprimer tous les logs de backup
     */
    public function clearLogs()
    {
        BackupLog::truncate();

        return redirect()->route('admin.backup.index')
            ->with('success', 'Les logs de backup ont été effacés avec succès');
    }

     /**
     * Tester la configuration email
     */
    public function testEmail()
    {
        try {
            $settings = BackupSetting::first();
            $testEmail = $settings ? $settings->backup_email : config('mail.from.address');

            if (!$testEmail) {
                return response()->json(['success' => false, 'message' => 'Aucun email configuré'], 400);
            }

            $companyInfo = CompanyInfo::first();
            $companyName = $companyInfo->name ?? config('app.name');

            $data = [
                'company_name' => $companyName,
                'test_date' => date('d/m/Y H:i:s'),
                'message' => 'Ceci est un email de test pour vérifier la configuration des backups.'
            ];

            // Version corrigée : passer les données correctement
            Mail::send('emails.backup-test', $data, function($message) use ($testEmail) {
                $message->to($testEmail)
                        ->subject('Test de configuration backup - ' . config('app.name'));
            });

            return response()->json(['success' => true, 'message' => 'Email de test envoyé avec succès']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    /**
     * Obtenir l'espace disque disponible
     */
    public function getDiskSpace()
    {
        $freeSpace = disk_free_space(storage_path());
        $totalSpace = disk_total_space(storage_path());

        return response()->json([
            'free' => $this->formatSize($freeSpace),
            'total' => $this->formatSize($totalSpace),
            'used_percent' => round((($totalSpace - $freeSpace) / $totalSpace) * 100, 2),
            'free_percent' => round(($freeSpace / $totalSpace) * 100, 2)
        ]);
    }

    /**
     * Créer le fichier de backup
     */
    private function createBackup($type = 'full')
    {
        $backupDir = storage_path('app/backups');

        if (!File::exists($backupDir)) {
            File::makeDirectory($backupDir, 0755, true);
        }

        $filename = 'backup_' . date('Y-m-d_H-i-s') . '.zip';
        $backupPath = $backupDir . '/' . $filename;

        $zip = new ZipArchive();

        if ($zip->open($backupPath, ZipArchive::CREATE) !== true) {
            throw new \Exception('Impossible de créer le fichier ZIP');
        }

        // Backup de la base de données
        if ($type == 'full' || $type == 'database') {
            $dbBackup = $this->backupDatabase();
            if ($dbBackup && File::exists($dbBackup)) {
                $zip->addFile($dbBackup, 'database.sql');
            }
        }

        // Backup des fichiers
        if ($type == 'full' || $type == 'files') {
            // Dossier public storage
            $publicStorage = storage_path('app/public');
            if (File::exists($publicStorage)) {
                $this->addFolderToZip($zip, $publicStorage, 'storage');
            }

            // Dossier des vues
            $viewsPath = base_path('resources/views');
            if (File::exists($viewsPath)) {
                $this->addFolderToZip($zip, $viewsPath, 'views');
            }

            // Fichiers de configuration
            $configFiles = ['.env', 'config/app.php', 'config/database.php', 'config/mail.php'];
            foreach ($configFiles as $file) {
                $filePath = base_path($file);
                if (File::exists($filePath)) {
                    $zip->addFile($filePath, 'config/' . basename($file));
                }
            }
        }

        $zip->close();

        // Nettoyer le fichier temporaire de la base de données
        if (isset($dbBackup) && File::exists($dbBackup)) {
            File::delete($dbBackup);
        }

        return $backupPath;
    }

    /**
     * Backup de la base de données
     */
    private function backupDatabase()
    {
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');

        $backupFile = storage_path('app/backups/temp_db_backup_' . time() . '.sql');

        // Vérifier si mysqldump est disponible
        $mysqldumpPath = $this->findMysqldump();

        if ($mysqldumpPath && function_exists('exec')) {
            $command = sprintf(
                '"%s" --host=%s --user=%s --password=%s %s > "%s"',
                $mysqldumpPath,
                escapeshellarg($host),
                escapeshellarg($username),
                escapeshellarg($password),
                escapeshellarg($database),
                escapeshellarg($backupFile)
            );

            exec($command, $output, $returnVar);

            if ($returnVar === 0 && File::exists($backupFile) && File::size($backupFile) > 0) {
                return $backupFile;
            }
        }

        // Fallback: exporter via Laravel
        return $this->exportDatabaseViaLaravel($backupFile);
    }

    /**
     * Trouver le chemin de mysqldump
     */
    private function findMysqldump()
    {
        $paths = [
            'mysqldump',
            '/usr/bin/mysqldump',
            '/usr/local/bin/mysqldump',
            'C:\\xampp\\mysql\\bin\\mysqldump.exe',
            'C:\\Program Files\\MySQL\\MySQL Server 8.0\\bin\\mysqldump.exe',
            'C:\\Program Files\\MySQL\\MySQL Server 5.7\\bin\\mysqldump.exe'
        ];

        foreach ($paths as $path) {
            if (file_exists($path) || (function_exists('exec') && exec("where $path 2>NUL") !== '')) {
                return $path;
            }
        }

        return null;
    }

    /**
     * Export de la base de données via Laravel (fallback)
     */
    private function exportDatabaseViaLaravel($backupFile)
    {
        $tables = DB::select('SHOW TABLES');
        $databaseName = config('database.connections.mysql.database');
        $tableKey = 'Tables_in_' . $databaseName;

        $sql = "-- Backup généré le " . date('Y-m-d H:i:s') . "\n";
        $sql .= "-- Serveur: " . config('app.url') . "\n\n";
        $sql .= "SET FOREIGN_KEY_CHECKS=0;\n";
        $sql .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
        $sql .= "SET AUTOCOMMIT = 0;\n";
        $sql .= "START TRANSACTION;\n\n";

        foreach ($tables as $table) {
            $tableName = $table->$tableKey;

            // Structure de la table
            $createTable = DB::select("SHOW CREATE TABLE {$tableName}");
            $sql .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
            $sql .= $createTable[0]->{'Create Table'} . ";\n\n";

            // Données de la table
            $rows = DB::table($tableName)->get();
            if ($rows->count() > 0) {
                $rowsArray = $rows->toArray();
                $batchSize = 100;
                $batches = array_chunk($rowsArray, $batchSize);

                foreach ($batches as $batch) {
                    $values = [];
                    foreach ($batch as $row) {
                        $rowValues = array_map(function($value) {
                            if (is_null($value)) return 'NULL';
                            if (is_bool($value)) return $value ? '1' : '0';
                            return DB::getPdo()->quote($value);
                        }, (array)$row);
                        $values[] = "(" . implode(', ', $rowValues) . ")";
                    }
                    $sql .= "INSERT INTO `{$tableName}` VALUES \n" . implode(",\n", $values) . ";\n";
                }
                $sql .= "\n";
            }
        }

        $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";
        $sql .= "COMMIT;\n";

        File::put($backupFile, $sql);

        return $backupFile;
    }

    /**
     * Restaurer un backup
     */
    private function restoreBackup($backupPath)
    {
        $zip = new ZipArchive();

        if ($zip->open($backupPath) === true) {
            $tempDir = storage_path('app/backups/temp_restore_' . time());
            $zip->extractTo($tempDir);
            $zip->close();

            // Restaurer la base de données
            $sqlFile = $tempDir . '/database.sql';
            if (File::exists($sqlFile)) {
                $this->restoreDatabase($sqlFile);
            }

            // Nettoyer
            File::deleteDirectory($tempDir);
        }
    }

    /**
     * Restaurer la base de données
     */
    private function restoreDatabase($sqlFile)
    {
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');

        $sql = File::get($sqlFile);

        // Exécuter le SQL via PDO
        DB::unprepared($sql);
    }

    /**
     * Ajouter un dossier au zip
     */
    private function addFolderToZip($zip, $folder, $zipFolder = null)
    {
        if (!File::exists($folder)) {
            return;
        }

        $files = File::allFiles($folder);

        foreach ($files as $file) {
            $relativePath = $zipFolder ? $zipFolder . '/' . $file->getRelativePathname() : $file->getRelativePathname();
            $zip->addFile($file->getRealPath(), $relativePath);
        }
    }

    /**
     * Récupérer la liste des fichiers de backup
     */
    private function getBackupFiles()
    {
        $backupDir = storage_path('app/backups');

        if (!File::exists($backupDir)) {
            return collect();
        }

        $files = File::files($backupDir);

        $backups = [];
        foreach ($files as $file) {
            if (strpos($file->getFilename(), 'backup_') === 0 && $file->getExtension() === 'zip') {
                $backups[] = (object)[
                    'name' => $file->getFilename(),
                    'size' => $this->formatSize($file->getSize()),
                    'date' => date('d/m/Y H:i:s', $file->getMTime()),
                    'timestamp' => $file->getMTime(),
                    'path' => $file->getRealPath()
                ];
            }
        }

        // Trier par date décroissante
        usort($backups, function($a, $b) {
            return $b->timestamp - $a->timestamp;
        });

        return collect($backups);
    }

    /**
     * Formater la taille du fichier
     */
    private function formatSize($bytes)
    {
        if ($bytes <= 0) return '0 B';

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Nettoyer les anciens backups
     */
    private function cleanOldBackups($daysToKeep)
    {
        $backupDir = storage_path('app/backups');

        if (!File::exists($backupDir)) {
            return;
        }

        $files = File::files($backupDir);
        $now = time();
        $deletedCount = 0;

        foreach ($files as $file) {
            if (strpos($file->getFilename(), 'backup_') === 0 && $file->getExtension() === 'zip') {
                $fileAge = $now - $file->getMTime();
                $daysOld = $fileAge / (60 * 60 * 24);

                if ($daysOld > $daysToKeep) {
                    File::delete($file->getRealPath());
                    $deletedCount++;
                    Log::info('Ancien backup supprimé: ' . $file->getFilename());
                }
            }
        }

        if ($deletedCount > 0) {
            $this->notifyAdmins(
                'backup',
                'Nettoyage des anciennes sauvegardes',
                "{$deletedCount} ancien(s) fichier(s) de sauvegarde ont été supprimés.",
                route('admin.backup.index')
            );
        }
    }

    /**
     * Notifier les admins
     */
    private function notifyAdmins($type, $title, $message, $url = null)
    {
        $admins = User::whereHas('roles', function($query) {
            $query->whereIn('name', ['super-admin', 'admin']);
        })->get();

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'url' => $url,
                'is_read' => false
            ]);
        }
    }

   /**
     * Envoyer l'email de backup
     */
    private function sendBackupEmail($backupFile, $type = 'auto')
    {
        try {
            $settings = BackupSetting::first();
            $backupEmail = $settings ? $settings->backup_email : config('mail.from.address');

            if (!$backupEmail) {
                Log::warning('Aucun email configuré pour les backups');
                return false;
            }

            $companyInfo = CompanyInfo::first();
            $companyName = $companyInfo->name ?? config('app.name');

            $data = [
                'company_name' => $companyName,
                'backup_date' => date('d/m/Y H:i:s'),
                'backup_type' => $type === 'manual' ? 'Manuelle' : 'Automatique',
                'backup_size' => File::exists($backupFile) ? $this->formatSize(File::size($backupFile)) : 'N/A',
                'backup_filename' => basename($backupFile),
                'backup_path' => route('admin.backup.download', basename($backupFile))
            ];

            Mail::send('emails.backup-notification', $data, function($message) use ($backupEmail, $backupFile, $companyName) {
                $message->to($backupEmail)
                        ->subject("[" . $companyName . "] Sauvegarde du " . date('d/m/Y H:i'))
                        ->attach($backupFile, [
                            'as' => 'backup_' . date('Y-m-d_H-i-s') . '.zip',
                            'mime' => 'application/zip'
                        ]);
            });

            Log::info('Email de backup envoyé à: ' . $backupEmail);
            return true;

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de l\'email de backup: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtenir les statistiques des backups
     */
    private function getBackupStats()
    {
        $stats = [
            'total_backups' => BackupLog::count(),
            'successful_backups' => BackupLog::where('status', 'success')->count(),
            'failed_backups' => BackupLog::where('status', 'failed')->count(),
            'total_size' => '0 B',
            'last_backup' => BackupLog::where('status', 'success')->latest()->first(),
            'backups_by_month' => BackupLog::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
                ->groupBy('month')
                ->orderBy('month', 'desc')
                ->limit(6)
                ->get()
        ];

        // Calculer la taille totale des backups
        $backupDir = storage_path('app/backups');
        if (File::exists($backupDir)) {
            $files = File::files($backupDir);
            $totalSize = 0;
            foreach ($files as $file) {
                if (strpos($file->getFilename(), 'backup_') === 0) {
                    $totalSize += $file->getSize();
                }
            }
            $stats['total_size'] = $this->formatSize($totalSize);
        }

        return (object) $stats;
    }
}
