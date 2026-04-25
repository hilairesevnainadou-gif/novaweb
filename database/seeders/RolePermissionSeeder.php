<?php
// database/seeders/RolePermissionSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // ── Reset du cache ────────────────────────────────────────────────
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ══════════════════════════════════════════════════════════════════
        //  LISTE COMPLÈTE DES PERMISSIONS
        //  (synchronisée avec toutes les routes de admin.php)
        // ══════════════════════════════════════════════════════════════════
        $permissions = [

            // ── Dashboard ─────────────────────────────────────────────────
            'dashboard.view',

            // ── Utilisateurs ──────────────────────────────────────────────
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'users.assign.roles',
            'users.reset-password',
            'users.toggle-status',
            'users.resend-invitation',

            // ── Portfolio ─────────────────────────────────────────────────
            'portfolio.view',
            'portfolio.create',
            'portfolio.edit',
            'portfolio.delete',
            'portfolio.publish',
            'portfolio.reorder',

            // ── Blog ──────────────────────────────────────────────────────
            'blog.view',
            'blog.create',
            'blog.edit',
            'blog.delete',
            'blog.publish',
            'blog.duplicate',

            // ── Services ──────────────────────────────────────────────────
            'services.view',
            'services.create',
            'services.edit',
            'services.delete',
            'services.reorder',

            // ── Témoignages ───────────────────────────────────────────────
            'testimonials.view',
            'testimonials.create',
            'testimonials.edit',
            'testimonials.delete',

            // ── Contacts ──────────────────────────────────────────────────
            'contact.view',
            'contact.delete',
            'contact.reply',

            // ── Tickets ───────────────────────────────────────────────────
            'tickets.view',
            'tickets.create',
            'tickets.edit',
            'tickets.delete',
            'tickets.assign',

            // ── Maintenance ───────────────────────────────────────────────
            'maintenance.view',
            'maintenance.access',
            'maintenance.export',
            'maintenance.statistics',

            // ── Interventions ─────────────────────────────────────────────
            'interventions.view.all',
            'interventions.view',
            'interventions.create',
            'interventions.edit',
            'interventions.delete',
            'interventions.assign',
            'interventions.rate',

            // ── Devices ───────────────────────────────────────────────────
            'devices.view',
            'devices.create',
            'devices.edit',
            'devices.delete',

            // ── Sauvegardes ───────────────────────────────────────────────
            'backups.view',
            'backups.create',
            'backups.restore',
            'backups.delete',

            // ── Paramètres ────────────────────────────────────────────────
            'settings.view',
            'settings.edit',

            // ── Profil ────────────────────────────────────────────────────
            'profile.view',
            'profile.edit',

            // ── Rôles & Permissions ────────────────────────────────────────
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',

            // ── Newsletter ────────────────────────────────────────────────
            'newsletter.view',
            'newsletter.edit',
            'newsletter.delete',

            // ── Tools ─────────────────────────────────────────────────────
            'tools.view',
            'tools.create',
            'tools.edit',
            'tools.delete',

            // ── FAQ ───────────────────────────────────────────────────────
            'faqs.view',
            'faqs.create',
            'faqs.edit',
            'faqs.delete',

            // ── Clients ───────────────────────────────────────────────────
            'clients.view',
            'clients.create',
            'clients.edit',
            'clients.delete',

            // ── Billing ───────────────────────────────────────────────────
            'billing.view',
            'billing.invoices.view',
            'billing.invoices.create',
            'billing.invoices.edit',
            'billing.invoices.delete',
            'billing.invoices.send',
            'billing.payments.view',
            'billing.payments.create',
            'billing.payments.resend',

            // ── Team ──────────────────────────────────────────────────────
            'team.view',
            'team.create',
            'team.edit',
            'team.delete',

            // ── Projets ───────────────────────────────────────────────────
            'projects.view',        // ses propres projets
            'projects.view.all',    // tous les projets
            'projects.create',
            'projects.edit',
            'projects.delete',

            // ── Tâches ────────────────────────────────────────────────────
            'tasks.view',           // ses propres tâches
            'tasks.view.all',       // toutes les tâches
            'tasks.create',
            'tasks.edit',
            'tasks.delete',
            'tasks.approve',

            // ── Réunions ──────────────────────────────────────────────────
            'meetings.view',        // ses propres réunions
            'meetings.view.all',    // toutes les réunions
            'meetings.create',
            'meetings.edit',
            'meetings.edit.all',    // modifier réunions des autres
            'meetings.delete',
            'meetings.delete.all',  // supprimer réunions des autres
        ];

        // ── Créer toutes les permissions (idempotent) ─────────────────────
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name'       => $permission,
                'guard_name' => 'web',
            ]);
        }

        // ══════════════════════════════════════════════════════════════════
        //  RÔLES ET LEURS PERMISSIONS
        // ══════════════════════════════════════════════════════════════════
        $roles = [

            // ── Super Admin — tout ────────────────────────────────────────
            'super-admin' => [
                'display_name' => 'Super Admin',
                'description'  => 'Accès complet à toutes les fonctionnalités',
                'permissions'  => Permission::all()->pluck('name')->toArray(),
            ],

            // ── Admin ─────────────────────────────────────────────────────
            'admin' => [
                'display_name' => 'Administrateur',
                'description'  => 'Gestion complète du contenu sans accès aux paramètres système',
                'permissions'  => [
                    'dashboard.view',

                    // Newsletter
                    'newsletter.view', 'newsletter.edit', 'newsletter.delete',

                    // Utilisateurs
                    'users.view', 'users.create', 'users.edit', 'users.delete',
                    'users.assign.roles', 'users.reset-password',
                    'users.toggle-status', 'users.resend-invitation',

                    // Portfolio
                    'portfolio.view', 'portfolio.create', 'portfolio.edit',
                    'portfolio.delete', 'portfolio.publish', 'portfolio.reorder',

                    // Blog
                    'blog.view', 'blog.create', 'blog.edit',
                    'blog.delete', 'blog.publish', 'blog.duplicate',

                    // Services
                    'services.view', 'services.create', 'services.edit',
                    'services.delete', 'services.reorder',

                    // Témoignages
                    'testimonials.view', 'testimonials.create',
                    'testimonials.edit', 'testimonials.delete',

                    // Contacts
                    'contact.view', 'contact.delete', 'contact.reply',

                    // Tickets
                    'tickets.view', 'tickets.create', 'tickets.edit',
                    'tickets.delete', 'tickets.assign',

                    // Maintenance
                    'maintenance.view', 'maintenance.access',
                    'maintenance.export', 'maintenance.statistics',

                    // Interventions
                    'interventions.view.all', 'interventions.view',
                    'interventions.create', 'interventions.edit',
                    'interventions.delete', 'interventions.assign',
                    'interventions.rate',

                    // Devices
                    'devices.view', 'devices.create', 'devices.edit', 'devices.delete',

                    // Paramètres
                    'settings.view', 'settings.edit',

                    // Profil
                    'profile.view', 'profile.edit',

                    // Tools
                    'tools.view', 'tools.create', 'tools.edit', 'tools.delete',

                    // FAQ
                    'faqs.view', 'faqs.create', 'faqs.edit', 'faqs.delete',

                    // Clients
                    'clients.view', 'clients.create', 'clients.edit', 'clients.delete',

                    // Billing
                    'billing.view',
                    'billing.invoices.view', 'billing.invoices.create',
                    'billing.invoices.edit', 'billing.invoices.delete',
                    'billing.invoices.send',
                    'billing.payments.view', 'billing.payments.create',
                    'billing.payments.resend',

                    // Team
                    'team.view', 'team.create', 'team.edit', 'team.delete',

                    // Projets
                    'projects.view', 'projects.view.all',
                    'projects.create', 'projects.edit', 'projects.delete',

                    // Tâches
                    'tasks.view', 'tasks.view.all',
                    'tasks.create', 'tasks.edit', 'tasks.delete', 'tasks.approve',

                    // Réunions
                    'meetings.view', 'meetings.view.all',
                    'meetings.create', 'meetings.edit', 'meetings.edit.all',
                    'meetings.delete', 'meetings.delete.all',
                ],
            ],

            // ── Chef de Projet ────────────────────────────────────────────
            'project-manager' => [
                'display_name' => 'Chef de Projet',
                'description'  => 'Gestion complète des projets, tâches et réunions',
                'permissions'  => [
                    'dashboard.view',

                    // Projets
                    'projects.view', 'projects.view.all',
                    'projects.create', 'projects.edit', 'projects.delete',

                    // Tâches
                    'tasks.view', 'tasks.view.all',
                    'tasks.create', 'tasks.edit', 'tasks.delete', 'tasks.approve',

                    // Réunions
                    'meetings.view', 'meetings.view.all',
                    'meetings.create', 'meetings.edit', 'meetings.edit.all',
                    'meetings.delete', 'meetings.delete.all',

                    // Clients (lecture seule)
                    'clients.view',

                    // Profil
                    'profile.view', 'profile.edit',
                ],
            ],

            // ── Éditeur ───────────────────────────────────────────────────
            'editor' => [
                'display_name' => 'Éditeur',
                'description'  => 'Gestion du contenu (portfolio, blog, services, témoignages)',
                'permissions'  => [
                    'dashboard.view',

                    'portfolio.view', 'portfolio.create', 'portfolio.edit',
                    'portfolio.publish', 'portfolio.reorder',

                    'blog.view', 'blog.create', 'blog.edit',
                    'blog.publish', 'blog.duplicate',

                    'services.view', 'services.create',
                    'services.edit', 'services.reorder',

                    'testimonials.view', 'testimonials.create', 'testimonials.edit',

                    'tools.view', 'tools.create', 'tools.edit',

                    'faqs.view', 'faqs.create', 'faqs.edit',

                    'newsletter.view',

                    // Réunions (ses propres uniquement)
                    'meetings.view',

                    'profile.view', 'profile.edit',
                ],
            ],

            // ── Manager Technicien ────────────────────────────────────────
            'tech-manager' => [
                'display_name' => 'Manager Technicien',
                'description'  => 'Gestion des techniciens, assignation des interventions et facturation',
                'permissions'  => [
                    'dashboard.view',

                    // Interventions (gestion complète + assignation)
                    'interventions.view.all', 'interventions.view',
                    'interventions.create', 'interventions.edit',
                    'interventions.delete', 'interventions.assign',
                    'interventions.rate',

                    // Devices (gestion complète)
                    'devices.view', 'devices.create', 'devices.edit', 'devices.delete',

                    // Maintenance
                    'maintenance.view', 'maintenance.access',
                    'maintenance.export', 'maintenance.statistics',

                    // Tickets
                    'tickets.view', 'tickets.create', 'tickets.edit', 'tickets.assign',

                    // Clients (lecture pour facturation)
                    'clients.view',

                    // Facturation
                    'billing.view',
                    'billing.invoices.view', 'billing.invoices.create',
                    'billing.invoices.edit', 'billing.invoices.delete',
                    'billing.invoices.send',
                    'billing.payments.view', 'billing.payments.create',
                    'billing.payments.resend',

                    // Réunions (ses propres — peut être invité à des réunions inter-équipes)
                    'meetings.view', 'meetings.create', 'meetings.edit',

                    // Projets & tâches (peut être membre d'un projet)
                    'projects.view', 'tasks.view',

                    // Profil
                    'profile.view', 'profile.edit',
                ],
            ],

            // ── Développeur ───────────────────────────────────────────────
            'developer' => [
                'display_name' => 'Développeur',
                'description'  => 'Intervient sur les projets et tâches assignés, sans création de projet',
                'permissions'  => [
                    'dashboard.view',

                    // Projets (lecture seule — ne crée pas de projet)
                    'projects.view',

                    // Tâches (peut travailler sur les tâches assignées)
                    'tasks.view', 'tasks.create', 'tasks.edit',

                    // Réunions (participation)
                    'meetings.view', 'meetings.create', 'meetings.edit',

                    // Profil
                    'profile.view', 'profile.edit',
                ],
            ],

            // ── Support ───────────────────────────────────────────────────
            'support' => [
                'display_name' => 'Support Technique',
                'description'  => 'Gestion des tickets et des contacts clients',
                'permissions'  => [
                    'dashboard.view',

                    // Tickets
                    'tickets.view', 'tickets.create', 'tickets.edit',
                    'tickets.delete', 'tickets.assign',

                    // Interventions (vue uniquement — l'assignation revient au Manager)
                    'interventions.view',

                    // Maintenance (vue)
                    'maintenance.view',

                    // Contacts
                    'contact.view', 'contact.reply',

                    // Réunions (ses propres — peut être convoqué à des réunions)
                    'meetings.view',

                    // Profil
                    'profile.view', 'profile.edit',
                ],
            ],

            // ── Technicien ────────────────────────────────────────────────
            'technician' => [
                'display_name' => 'Technicien',
                'description'  => 'Réalise les interventions assignées par le Manager Technicien',
                'permissions'  => [
                    'dashboard.view',

                    // Interventions (travaille sur ce qui lui est assigné — pas d'assignation)
                    'interventions.view', 'interventions.create',
                    'interventions.edit', 'interventions.rate',

                    // Maintenance (vue)
                    'maintenance.view',

                    // Devices (vue)
                    'devices.view',

                    // Réunions (ses propres — peut être convoqué à des réunions)
                    'meetings.view',

                    // Profil
                    'profile.view', 'profile.edit',
                ],
            ],

            // ── Visualisateur ─────────────────────────────────────────────
            'viewer' => [
                'display_name' => 'Visualisateur',
                'description'  => 'Accès en lecture seule sur l\'ensemble du contenu',
                'permissions'  => [
                    'dashboard.view',
                    'portfolio.view',
                    'blog.view',
                    'services.view',
                    'testimonials.view',
                    'contact.view',
                    'tickets.view',
                    'maintenance.view',
                    'interventions.view',
                    'devices.view',
                    'projects.view',
                    'tasks.view',
                    'meetings.view',
                    'profile.view',
                ],
            ],
        ];

        // ── Créer les rôles et synchroniser les permissions ───────────────
        foreach ($roles as $roleKey => $roleData) {
            $role = Role::firstOrCreate([
                'name'       => $roleKey,
                'guard_name' => 'web',
            ]);

            $role->syncPermissions($roleData['permissions']);

            $role->display_name = $roleData['display_name'];
            $role->description  = $roleData['description'];
            $role->save();
        }

        // ── Utilisateurs de test ──────────────────────────────────────────
        $testUsers = [
            ['email' => 'admin@novatech.com',          'name' => 'Super Admin',              'role' => 'super-admin',     'password' => 'NovaTech@2026!'],
            ['email' => 'projectmanager@novatech.com', 'name' => 'Chef de Projet Test',      'role' => 'project-manager', 'password' => 'password'],
            ['email' => 'techmanager@novatech.com',    'name' => 'Manager Technicien Test',  'role' => 'tech-manager',    'password' => 'password'],
            ['email' => 'developer@novatech.com',      'name' => 'Développeur Test',         'role' => 'developer',       'password' => 'password'],
            ['email' => 'technicien@novatech.com',     'name' => 'Technicien Test',          'role' => 'technician',      'password' => 'password'],
            ['email' => 'support@novatech.com',        'name' => 'Support Test',             'role' => 'support',         'password' => 'password'],
            ['email' => 'editor@novatech.com',         'name' => 'Éditeur Test',             'role' => 'editor',          'password' => 'password'],
            ['email' => 'viewer@novatech.com',         'name' => 'Visualisateur Test',       'role' => 'viewer',          'password' => 'password'],
        ];

        foreach ($testUsers as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name'              => $userData['name'],
                    'password'          => bcrypt($userData['password']),
                    'email_verified_at' => now(),
                    'is_active'         => true,
                ]
            );
            if (! $user->hasRole($userData['role'])) {
                $user->assignRole($userData['role']);
            }
        }

        $this->command->info('✅ RolePermissionSeeder exécuté avec succès !');
        $this->command->newLine();

        $this->command->table(
            ['Rôle', 'Description', 'Permissions attribuées'],
            collect($roles)->map(fn($r, $k) => [
                $k,
                $r['description'],
                count($r['permissions']) . ' permissions',
            ])->values()->toArray()
        );

        $this->command->newLine();
        $this->command->info('📋 Utilisateurs créés :');
        $this->command->table(
            ['Email', 'Rôle', 'Mot de passe'],
            collect($testUsers)->map(fn($u) => [
                $u['email'], $u['role'], $u['password'],
            ])->toArray()
        );
    }
}
