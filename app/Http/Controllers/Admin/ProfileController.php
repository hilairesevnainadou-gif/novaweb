<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Afficher le formulaire d'édition du profil
     */
    public function edit()
    {
        $user = Auth::user();
        return view('admin.profile.edit', compact('user'));
    }

    /**
     * Mettre à jour le profil
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('admin.profile.edit')
            ->with('success', 'Profil mis à jour avec succès');
    }

    /**
     * Mettre à jour le mot de passe
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string|min:8',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()
                ->withErrors(['current_password' => 'Le mot de passe actuel est incorrect']);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('admin.profile.edit')
            ->with('success', 'Mot de passe mis à jour avec succès');
    }

    /**
     * Mettre à jour l'avatar
     */
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = Auth::user();

        // Supprimer l'ancien avatar s'il existe
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Upload du nouvel avatar
        $path = $request->file('avatar')->store('avatars', 'public');

        $user->update(['avatar' => $path]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Avatar mis à jour avec succès',
                'avatar_url' => Storage::url($path)
            ]);
        }

        return redirect()->route('admin.profile.edit')
            ->with('success', 'Avatar mis à jour avec succès');
    }

    /**
     * Renvoyer l'email de vérification
     */
    public function resendVerification(Request $request)
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email déjà vérifié'
                ]);
            }
            return redirect()->route('admin.profile.edit')
                ->with('info', 'Votre email est déjà vérifié');
        }

        $user->sendEmailVerificationNotification();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Email de vérification envoyé'
            ]);
        }

        return redirect()->route('admin.profile.edit')
            ->with('success', 'Email de vérification envoyé');
    }
}
