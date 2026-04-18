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
            'users.reset-password',      // ← ajout : route /{user}/reset-password
            'users.toggle-status',       // ← ajout : route /{user}/toggle-status

            // ── Portfolio ─────────────────────────────────────────────────
            'portfolio.view',
            'portfolio.create',
            'portfolio.edit',
            'portfolio.delete',
            'portfolio.publish',
            'portfolio.reorder',         // ← ajout : route /reorder

            // ── Blog ──────────────────────────────────────────────────────
            'blog.view',
            'blog.create',
            'blog.edit',
            'blog.delete',
            'blog.publish',
            'blog.duplicate',            // ← ajout : route /{blog}/duplicate

            // ── Services ──────────────────────────────────────────────────
            'services.view',
            'services.create',
            'services.edit',
            'services.delete',
            'services.reorder',          // ← ajout : route /reorder

            // ── Témoignages ───────────────────────────────────────────────
            'testimonials.view',
            'testimonials.create',
            'testimonials.edit',
            'testimonials.delete',

            // ── Contacts ──────────────────────────────────────────────────
            'contact.view',
            'contact.delete',
            'contact.reply',             // ← ajout : cohérence métier

            // ── Tickets ───────────────────────────────────────────────────
            'tickets.view',
            'tickets.create',
            'tickets.edit',
            'tickets.delete',
            'tickets.assign',            // ← ajout : manquait dans le tableau principal

            // ── Maintenance ───────────────────────────────────────────────
            'maintenance.view',
            'maintenance.create',
            'maintenance.edit',
            'maintenance.delete',        // ← ajout : cohérence CRUD

            // ── Sauvegardes ───────────────────────────────────────────────
            'backups.view',
            'backups.create',
            'backups.restore',
            'backups.delete',            // ← ajout : cohérence CRUD

            // ── Paramètres ────────────────────────────────────────────────
            'settings.view',
            'settings.edit',

            // ── Profil ────────────────────────────────────────────────────
            'profile.view',              // ← ajout : route /admin/profile (GET)
            'profile.edit',              // ← ajout : route /admin/profile (PUT)

            // ── Rôles & Permissions (gestion UI) ──────────────────────────
            'roles.view',                // ← ajout : pour la vue de gestion
            'roles.create',              // ← ajout
            'roles.edit',                // ← ajout
            'roles.delete',              // ← ajout

            // Ajoutez dans le tableau $permissions
            'newsletter.view',
            'newsletter.edit',
            'newsletter.delete',
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
                    'users.view',
                    'users.create',
                    'users.edit',
                    'users.delete',
                    'users.assign.roles',
                    'users.reset-password',
                    'users.toggle-status',

                    'portfolio.view',
                    'portfolio.create',
                    'portfolio.edit',
                    'portfolio.delete',
                    'portfolio.publish',
                    'portfolio.reorder',

                    'blog.view',
                    'blog.create',
                    'blog.edit',
                    'blog.delete',
                    'blog.publish',
                    'blog.duplicate',

                    'services.view',
                    'services.create',
                    'services.edit',
                    'services.delete',
                    'services.reorder',

                    'testimonials.view',
                    'testimonials.create',
                    'testimonials.edit',
                    'testimonials.delete',

                    'contact.view',
                    'contact.delete',
                    'contact.reply',

                    'tickets.view',
                    'tickets.create',
                    'tickets.edit',
                    'tickets.delete',
                    'tickets.assign',

                    'settings.view',
                    'settings.edit',

                    'profile.view',
                    'profile.edit',
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
                'description'  => 'Gestion des tickets et de la maintenance',
                'permissions'  => [
                    'dashboard.view',

                    'tickets.view',
                    'tickets.create',
                    'tickets.edit',
                    'tickets.delete',
                    'tickets.assign',

                    'contact.view',
                    'contact.reply',

                    'maintenance.view',
                    'maintenance.create',
                    'maintenance.edit',

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
                'password' => bcrypt('NovaTech@2026!'),  // mot de passe plus sécurisé
            ]
        );

        if (! $superAdmin->hasRole('super-admin')) {
            $superAdmin->assignRole('super-admin');
        }

        $this->command->info(' RolePermissionSeeder exécuté avec succès.');
        $this->command->table(
            ['Rôle', 'Permissions attribuées'],
            collect($roles)->map(fn($r, $k) => [
                $k,
                count(
                    $r['permissions'] instanceof \Illuminate\Support\Collection
                        ? $r['permissions']->toArray()
                        : $r['permissions']
                ) . ' permissions',
            ])->values()->toArray()
        );
    }
}
