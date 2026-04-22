<?php
// app/Http/Controllers/Auth/InvitationController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class InvitationController extends Controller
{
    /**
     * Afficher le formulaire de définition du mot de passe
     */
    public function show(string $token)
    {
        $invitation = UserInvitation::where('token', $token)
            ->whereNull('accepted_at')
            ->with('user')
            ->first();

        if (!$invitation) {
            return view('auth.invitation-invalid', [
                'reason' => 'Ce lien d\'invitation est invalide ou a déjà été utilisé.'
            ]);
        }

        if ($invitation->isExpired()) {
            return view('auth.invitation-invalid', [
                'reason' => 'Ce lien d\'invitation a expiré (validité 72h). Contactez l\'administrateur.'
            ]);
        }

        // Déterminer le type : 'invitation' pour les nouveaux comptes, 'reset' pour réinitialisation
        $type = $invitation->user->email_verified_at ? 'reset' : 'invitation';

        return view('auth.invitation', compact('invitation', 'token', 'type'));
    }

    /**
     * Valider et enregistrer le mot de passe choisi
     */
    public function accept(Request $request, string $token)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
            'type' => 'nullable|string|in:invitation,reset'
        ], [
            'password.min'       => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
        ]);

        $invitation = UserInvitation::where('token', $token)
            ->whereNull('accepted_at')
            ->with('user')
            ->first();

        if (!$invitation || $invitation->isExpired()) {
            return back()->withErrors(['token' => 'Lien invalide ou expiré.']);
        }

        $user = $invitation->user;

        // Définir le mot de passe
        $user->password = Hash::make($request->password);

        // Si c'est une invitation (pas encore vérifié), on vérifie l'email
        if (!$user->email_verified_at) {
            $user->email_verified_at = now();
        }

        $user->save();

        // Marquer l'invitation comme acceptée
        $invitation->update(['accepted_at' => now()]);

        // Connecter l'utilisateur automatiquement
        Auth::login($user);

        $message = $user->email_verified_at
            ? 'Votre mot de passe a été réinitialisé avec succès.'
            : 'Bienvenue ' . $user->name . ' ! Votre compte est activé.';

        return redirect()->intended(config('fortify.home', '/admin/dashboard'))
            ->with('success', $message);
    }
}
