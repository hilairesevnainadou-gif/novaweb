<?php
// app/Helpers/PermissionHelper.php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class PermissionHelper
{
    public static function can($permission)
    {
        return Auth::check() && Auth::user()->can($permission);
    }

    public static function hasRole($role)
    {
        return Auth::check() && Auth::user()->hasRole($role);
    }

    public static function getUserRoles()
    {
        return Auth::check() ? Auth::user()->getRoleNames() : collect();
    }
}
