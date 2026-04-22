<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    /**
     * Déconnecter l'utilisateur et rediriger vers la page de connexion
     */
    public function __invoke(Request $request)
    {
        // Déconnecter l'utilisateur
        auth()->logout();

        // Invalider la session
        $request->session()->invalidate();

        // Régénérer le token CSRF
        $request->session()->regenerateToken();

        // Rediriger vers la page de connexion
        return redirect()->route('login');
    }
}
