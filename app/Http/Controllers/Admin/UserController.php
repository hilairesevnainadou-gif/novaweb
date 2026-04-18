<?php
// app/Http/Controllers/Admin/UserController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;  // CORRIGÉ : ajout du backslash manquant
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Afficher la liste des utilisateurs
     */
    public function index()
    {
        $users = User::with('roles', 'permissions')->paginate(10);
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Afficher le formulaire de création d'utilisateur
     */
    public function create()
    {
        $roles = Role::all();
        $permissions = Permission::all();

        return view('admin.users.create', compact('roles', 'permissions'));
    }

    /**
     * Enregistrer un nouvel utilisateur
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'roles' => 'nullable|array',
            'permissions' => 'nullable|array',
        ]);

        // Générer un mot de passe aléatoire
        $plainPassword = Str::random(10);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($plainPassword),
        ]);

        // Assigner les rôles
        if (!empty($validated['roles'])) {
            $user->assignRole($validated['roles']);
        }

        // Assigner les permissions directes
        if (!empty($validated['permissions'])) {
            $user->givePermissionTo($validated['permissions']);
        }

        // Envoyer l'email avec le mot de passe généré
        $this->sendWelcomeEmail($user, $plainPassword);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur créé avec succès. Un email avec ses identifiants lui a été envoyé.');
    }

    /**
     * Afficher le formulaire d'édition d'un utilisateur
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $permissions = Permission::all();
        $userRoles = $user->roles->pluck('name')->toArray();
        $userPermissions = $user->permissions->pluck('name')->toArray();

        return view('admin.users.edit', compact('user', 'roles', 'permissions', 'userRoles', 'userPermissions'));
    }

    /**
     * Mettre à jour un utilisateur
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'roles' => 'nullable|array',
            'permissions' => 'nullable|array',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->save();

        // Synchroniser les rôles
        if (isset($validated['roles'])) {
            $user->syncRoles($validated['roles']);
        }

        // Synchroniser les permissions directes
        if (isset($validated['permissions'])) {
            $user->syncPermissions($validated['permissions']);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    /**
     * Assigner les rôles et permissions à un utilisateur
     */
    public function assignRoles(Request $request, User $user)
    {
        $request->validate([
            'roles' => 'nullable|array',
            'permissions' => 'nullable|array',
        ]);

        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
        }

        if ($request->has('permissions')) {
            $user->syncPermissions($request->permissions);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Rôles et permissions mis à jour avec succès.'
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Rôles et permissions mis à jour avec succès.');
    }

    /**
     * Réinitialiser le mot de passe d'un utilisateur
     */
    public function resetPassword(Request $request, User $user)
    {
        try {
            // Vérifier si l'utilisateur peut être réinitialisé
            if ($user->id === auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous ne pouvez pas réinitialiser votre propre mot de passe.'
                ], 403);
            }

            // Générer un nouveau mot de passe aléatoire
            $newPassword = Str::random(10);

            // Mettre à jour le mot de passe
            $user->password = Hash::make($newPassword);
            $user->save();

            // Envoyer l'email avec le nouveau mot de passe
            Mail::send('emails.password-reset', [
                'user' => $user,
                'password' => $newPassword,
                'loginUrl' => route('login')
            ], function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Nouveau mot de passe - NovaTech')
                        ->from(config('mail.from.address'), config('mail.from.name'));
            });

            return response()->json([
                'success' => true,
                'message' => 'Un nouveau mot de passe a été envoyé à ' . $user->email
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Envoyer l'email de bienvenue avec le mot de passe généré
     */
    protected function sendWelcomeEmail(User $user, string $plainPassword)
    {
        try {
            Mail::send('emails.welcome', [
                'user' => $user,
                'password' => $plainPassword,
                'loginUrl' => route('login')
            ], function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Bienvenue sur NovaTech - Vos identifiants de connexion')
                        ->from(config('mail.from.address'), config('mail.from.name'));
            });
        } catch (\Exception $e) {
            Log::error('Erreur envoi email bienvenue: ' . $e->getMessage());
        }
    }

    /**
     * Envoyer l'email de réinitialisation de mot de passe
     */
    protected function sendPasswordResetEmail(User $user, string $newPassword)
    {
        try {
            Mail::send('emails.password-reset', [
                'user' => $user,
                'password' => $newPassword,
                'loginUrl' => route('login')
            ], function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Réinitialisation de votre mot de passe - NovaTech')
                        ->from(config('mail.from.address'), config('mail.from.name'));
            });
        } catch (\Exception $e) {
            Log::error('Erreur envoi email réinitialisation: ' . $e->getMessage());
        }
    }

    /**
     * Supprimer un utilisateur
     */
    public function destroy(Request $request, User $user)
    {
        // Empêcher la suppression de son propre compte
        if ($user->id === auth()->id()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous ne pouvez pas supprimer votre propre compte.'
                ], 403);
            }

            return redirect()->route('admin.users.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Utilisateur supprimé avec succès.'
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }

    /**
     * Activer/Désactiver un utilisateur (optionnel)
     * Nécessite l'ajout d'un champ 'is_active' dans la table users
     */
    public function toggleStatus(Request $request, User $user)
    {
        // Vérifier si le champ is_active existe dans la table
        if (!Schema::hasColumn('users', 'is_active')) {
            return response()->json([
                'success' => false,
                'message' => 'La fonctionnalité n\'est pas disponible. Champ is_active manquant.'
            ], 500);
        }

        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Vous ne pouvez pas modifier votre propre statut.'
            ], 403);
        }

        $user->is_active = !$user->is_active;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Statut de l\'utilisateur mis à jour avec succès.',
            'is_active' => $user->is_active
        ]);
    }

    /**
     * Vérifier si un email existe déjà (pour validation AJAX)
     */
    public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'exclude_id' => 'nullable|exists:users,id'
        ]);

        $exists = User::where('email', $request->email)
            ->when($request->exclude_id, function ($query) use ($request) {
                return $query->where('id', '!=', $request->exclude_id);
            })
            ->exists();

        return response()->json([
            'available' => !$exists
        ]);
    }
}
