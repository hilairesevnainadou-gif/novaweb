<?php
// app/Http/Controllers/Admin/UserController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\UserInvitationMail;
use App\Mail\UserPasswordResetInvitation;
use App\Models\User;
use App\Models\UserInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
     * Enregistrer un nouvel utilisateur avec système d'invitation
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'roles' => 'nullable|array',
            'permissions' => 'nullable|array',
        ]);

        // Créer l'utilisateur avec un mot de passe temporaire inutilisable
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make(Str::random(32)), // mot de passe aléatoire que l'utilisateur ne connaît pas
        ]);

        // Assigner les rôles
        if (!empty($validated['roles'])) {
            $user->assignRole($validated['roles']);
        }

        // Assigner les permissions directes
        if (!empty($validated['permissions'])) {
            $user->givePermissionTo($validated['permissions']);
        }

        // Générer l'invitation et envoyer l'email
        $invitation = UserInvitation::generateFor($user);
        $this->sendInvitationEmail($user, $invitation);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur créé avec succès. Un email d\'invitation lui a été envoyé pour définir son mot de passe.');
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

        // Vérifier si l'utilisateur a une invitation en attente
        $hasPendingInvitation = UserInvitation::where('user_id', $user->id)
            ->whereNull('accepted_at')
            ->exists();

        return view('admin.users.edit', compact('user', 'roles', 'permissions', 'userRoles', 'userPermissions', 'hasPendingInvitation'));
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
 * Renvoyer une invitation à un utilisateur (première invitation)
 */
public function resendInvitation(Request $request, User $user)
{
    try {
        // Vérifier si l'utilisateur a déjà accepté son invitation
        $existingInvitation = UserInvitation::where('user_id', $user->id)
            ->whereNotNull('accepted_at')
            ->first();

        if ($existingInvitation) {
            return response()->json([
                'success' => false,
                'message' => 'Cet utilisateur a déjà accepté son invitation et activé son compte.'
            ], 400);
        }

        // Vérifier si l'utilisateur a déjà un compte actif (email vérifié)
        if ($user->email_verified_at) {
            return response()->json([
                'success' => false,
                'message' => 'Cet utilisateur a déjà activé son compte. Utilisez "Réinitialiser le mot de passe" à la place.'
            ], 400);
        }

        // Générer une nouvelle invitation
        $invitation = UserInvitation::generateFor($user);
        $this->sendInvitationEmail($user, $invitation);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Invitation renvoyée avec succès à ' . $user->email
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Invitation renvoyée à ' . $user->email);

    } catch (\Exception $e) {
        Log::error('Erreur renvoi invitation: ' . $e->getMessage());

        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }

        return redirect()->route('admin.users.index')
            ->with('error', 'Erreur lors de l\'envoi de l\'invitation: ' . $e->getMessage());
    }
}
    /**
     * Envoyer l'email d'invitation
     */
    protected function sendInvitationEmail(User $user, UserInvitation $invitation): void
    {
        try {
            Mail::to($user->email)->send(new UserInvitationMail($user, $invitation));
        } catch (\Exception $e) {
            Log::error('Erreur envoi email invitation: ' . $e->getMessage());
        }
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
 * Réinitialiser le mot de passe d'un utilisateur (envoie une invitation)
 */
public function resetPassword(Request $request, User $user)
{
    try {
        // Vérifier si l'utilisateur peut être réinitialisé
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Vous ne pouvez pas réinitialiser votre propre mot de passe. Utilisez la fonction "Mot de passe oublié".'
            ], 403);
        }

        // Supprimer les anciennes invitations non acceptées
        UserInvitation::where('user_id', $user->id)
            ->whereNull('accepted_at')
            ->delete();

        // Générer une nouvelle invitation
        $invitation = UserInvitation::generateFor($user);
        $this->sendPasswordResetInvitation($user, $invitation);

        return response()->json([
            'success' => true,
            'message' => 'Une invitation pour réinitialiser son mot de passe a été envoyée à ' . $user->email
        ]);

    } catch (\Exception $e) {
        Log::error('Erreur réinitialisation mot de passe: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Erreur: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Envoyer l'email d'invitation pour réinitialisation
 */
protected function sendPasswordResetInvitation(User $user, UserInvitation $invitation): void
{
    try {
        Mail::to($user->email)->send(new UserPasswordResetInvitation($user, $invitation));
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

        // Supprimer les invitations associées
        UserInvitation::where('user_id', $user->id)->delete();

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
    public function toggleStatus(Request $request, User $user): \Illuminate\Http\JsonResponse
    {
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Vous ne pouvez pas modifier votre propre statut.',
            ], 403);
        }

        $user->is_active = ! $user->is_active;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => $user->is_active
                ? 'Compte activé avec succès.'
                : 'Compte désactivé avec succès.',
            'is_active' => $user->is_active,
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

    /**
     * Vérifier le statut d'invitation d'un utilisateur
     */
    public function checkInvitationStatus(User $user)
    {
        $invitation = UserInvitation::where('user_id', $user->id)
            ->whereNull('accepted_at')
            ->first();

        return response()->json([
            'has_pending_invitation' => !is_null($invitation),
            'invitation_expires_at' => $invitation?->expires_at?->format('d/m/Y H:i'),
            'is_expired' => $invitation?->isExpired() ?? false
        ]);
    }
}
