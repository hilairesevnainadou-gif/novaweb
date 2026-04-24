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

            // ── Maintenance (Dashboard et vue principale) ─────────────────
            'maintenance.view',
            'maintenance.access',

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

            // ── Maintenance reports & exports ─────────────────────────────
            'maintenance.export',
            'maintenance.statistics',

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

            // ── Rôles & Permissions (gestion UI) ──────────────────────────
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
            'projects.view',
            'projects.view.all',
            'projects.create',
            'projects.edit',
            'projects.delete',

            // ── Tâches ────────────────────────────────────────────────────
            'tasks.view',
            'tasks.view.all',
            'tasks.create',
            'tasks.edit',
            'tasks.delete',
            'tasks.approve',

            // ── Réunions ──────────────────────────────────────────────────
            'meetings.view',
            'meetings.view.all',
            'meetings.create',
            'meetings.edit',
            'meetings.delete',
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

            // ── Admin — tout sauf gestion des rôles et sauvegardes ────────
            'admin' => [
                'display_name' => 'Administrateur',
                'description'  => 'Gestion complète du contenu sans accès aux paramètres système',
                'permissions'  => [
                    'dashboard.view',
                    'newsletter.view', 'newsletter.edit', 'newsletter.delete',

                    // Utilisateurs
                    'users.view',
                    'users.create',
                    'users.edit',
                    'users.delete',
                    'users.assign.roles',
                    'users.reset-password',
                    'users.toggle-status',
                    'users.resend-invitation',

                    // Portfolio
                    'portfolio.view',
                    'portfolio.create',
                    'portfolio.edit',
                    'portfolio.delete',
                    'portfolio.publish',
                    'portfolio.reorder',

                    // Blog
                    'blog.view',
                    'blog.create',
                    'blog.edit',
                    'blog.delete',
                    'blog.publish',
                    'blog.duplicate',

                    // Services
                    'services.view',
                    'services.create',
                    'services.edit',
                    'services.delete',
                    'services.reorder',

                    // Témoignages
                    'testimonials.view',
                    'testimonials.create',
                    'testimonials.edit',
                    'testimonials.delete',

                    // Contacts
                    'contact.view',
                    'contact.delete',
                    'contact.reply',

                    // Tickets
                    'tickets.view',
                    'tickets.create',
                    'tickets.edit',
                    'tickets.delete',
                    'tickets.assign',

                    // Maintenance
                    'maintenance.view',
                    'maintenance.access',
                    'maintenance.export',
                    'maintenance.statistics',

                    // Interventions
                    'interventions.view.all',
                    'interventions.view',
                    'interventions.create',
                    'interventions.edit',
                    'interventions.delete',
                    'interventions.assign',
                    'interventions.rate',

                    // Devices
                    'devices.view',
                    'devices.create',
                    'devices.edit',
                    'devices.delete',

                    // Paramètres
                    'settings.view',
                    'settings.edit',

                    // Profil
                    'profile.view',
                    'profile.edit',

                    // Tools
                    'tools.view',
                    'tools.create',
                    'tools.edit',
                    'tools.delete',

                    // FAQ
                    'faqs.view',
                    'faqs.create',
                    'faqs.edit',
                    'faqs.delete',

                    // Clients
                    'clients.view',
                    'clients.create',
                    'clients.edit',
                    'clients.delete',

                    // Billing
                    'billing.view',
                    'billing.invoices.view',
                    'billing.invoices.create',
                    'billing.invoices.edit',
                    'billing.invoices.delete',
                    'billing.invoices.send',
                    'billing.payments.view',
                    'billing.payments.create',
                    'billing.payments.resend',

                    // Team
                    'team.view',
                    'team.create',
                    'team.edit',
                    'team.delete',

                    // Projets
                    'projects.view',
                    'projects.view.all',
                    'projects.create',
                    'projects.edit',
                    'projects.delete',

                    // Tâches
                    'tasks.view',
                    'tasks.view.all',
                    'tasks.create',
                    'tasks.edit',
                    'tasks.delete',
                    'tasks.approve',

                    // Réunions
                    'meetings.view',
                    'meetings.view.all',
                    'meetings.create',
                    'meetings.edit',
                    'meetings.delete',
                ],
            ],

            // ── Éditeur — contenu uniquement ──────────────────────────────
            'editor' => [
                'display_name' => 'Éditeur',
                'description'  => 'Gestion du contenu (portfolio, blog, services, témoignages)',
                'permissions'  => [
                    'dashboard.view',

                    'portfolio.view',
                    'portfolio.create',
                    'portfolio.edit',
                    'portfolio.publish',
                    'portfolio.reorder',

                    'blog.view',
                    'blog.create',
                    'blog.edit',
                    'blog.publish',
                    'blog.duplicate',

                    'services.view',
                    'services.create',
                    'services.edit',
                    'services.reorder',

                    'testimonials.view',
                    'testimonials.create',
                    'testimonials.edit',

                    'profile.view',
                    'profile.edit',
                ],
            ],

            // ── Support — tickets et maintenance ──────────────────────────
            'support' => [
                'display_name' => 'Support Technique',
                'description'  => 'Gestion des tickets, interventions et périphériques',
                'permissions'  => [
                    'dashboard.view',

                    'tickets.view',
                    'tickets.create',
                    'tickets.edit',
                    'tickets.delete',
                    'tickets.assign',

                    'maintenance.view',
                    'maintenance.access',

                    'interventions.view.all',
                    'interventions.view',
                    'interventions.create',
                    'interventions.edit',
                    'interventions.assign',
                    'interventions.rate',

                    'devices.view',
                    'devices.create',
                    'devices.edit',

                    'contact.view',
                    'contact.reply',

                    // Projets
                    'projects.view',
                    'projects.view.all',
                    'projects.create',
                    'projects.edit',

                    // Tâches
                    'tasks.view',
                    'tasks.view.all',
                    'tasks.create',
                    'tasks.edit',
                    'tasks.approve',

                    // Réunions
                    'meetings.view',
                    'meetings.view.all',
                    'meetings.create',
                    'meetings.edit',

                    'profile.view',
                    'profile.edit',
                ],
            ],

            // ── Technicien — interventions uniquement ─────────────────────
            'technician' => [
                'display_name' => 'Technicien',
                'description'  => 'Gestion des interventions et des périphériques',
                'permissions'  => [
                    'dashboard.view',

                    'maintenance.view',

                    'interventions.view',
                    'interventions.create',
                    'interventions.edit',
                    'interventions.rate',

                    // Projets (accès limité)
                    'projects.view',
                    'tasks.view',
                    'tasks.create',
                    'tasks.edit',
                    'meetings.view',

                    'profile.view',
                    'profile.edit',
                ],
            ],

            // ── Visualisateur — lecture seule ─────────────────────────────
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

        // ── Super Admin par défaut ────────────────────────────────────────
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@novatech.com'],
            [
                'name'     => 'Super Admin',
                'password' => bcrypt('NovaTech@2026!'),
            ]
        );

        if (! $superAdmin->hasRole('super-admin')) {
            $superAdmin->assignRole('super-admin');
        }

        // ── Créer un utilisateur technicien de test ───────────────────────
        $technician = User::firstOrCreate(
            ['email' => 'technicien@novatech.com'],
            [
                'name'     => 'Technicien Test',
                'password' => bcrypt('password'),
            ]
        );

        if (! $technician->hasRole('technician')) {
            $technician->assignRole('technician');
        }

        // ── Créer un utilisateur support de test ──────────────────────────
        $support = User::firstOrCreate(
            ['email' => 'support@novatech.com'],
            [
                'name'     => 'Support Test',
                'password' => bcrypt('password'),
            ]
        );

        if (! $support->hasRole('support')) {
            $support->assignRole('support');
        }

        $this->command->info('✅ RolePermissionSeeder exécuté avec succès !');
        $this->command->newLine();

        // Afficher le tableau des rôles
        $this->command->table(
            ['Rôle', 'Description', 'Permissions attribuées'],
            collect($roles)->map(fn($r, $k) => [
                $k,
                $r['description'],
                count($r['permissions']) . ' permissions'
            ])->values()->toArray()
        );

        // Afficher les utilisateurs créés
        $this->command->newLine();
        $this->command->info(' Utilisateurs créés :');
        $this->command->table(
            ['Email', 'Rôle', 'Mot de passe'],
            [
                ['admin@novatech.com', 'super-admin', 'NovaTech@2026!'],
                ['technicien@novatech.com', 'technician', 'password'],
                ['support@novatech.com', 'support', 'password'],
            ]
        );
    }
}
