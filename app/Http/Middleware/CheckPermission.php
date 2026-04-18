<?php
// app/Http/Middleware/CheckPermission.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (!Auth::user()->can($permission)) {
            abort(403, 'Vous n\'avez pas la permission d\'accéder à cette page.');
        }

        return $next($request);
    }
}
