<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleController extends Controller
{
    /** Liste des rôles + formulaire de création */
    public function index()
    {
        $roles = Role::with('permissions')
            ->withCount('users')
            ->get();

        $permissions = Permission::orderBy('name')->get();
        $totalUsers = User::count();

        return view('admin.roles.index', compact('roles', 'permissions', 'totalUsers'));
    }

    /** Créer un nouveau rôle */
    public function store(Request $request)
    {
        // Validation simplifiée
        $request->validate([
            'name'          => ['required', 'string', 'max:255', 'regex:/^[a-z][a-z0-9\-]*$/', 'unique:roles,name'],
            'display_name'  => ['required', 'string', 'max:255'],
            'description'   => ['nullable', 'string'],
            'color'         => ['nullable', 'string'],
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ]);

        // Création du rôle
        $role = Role::create([
            'name'         => $request->name,
            'guard_name'   => 'web',
            'display_name' => $request->display_name,
            'description'  => $request->description,
            'color'        => $request->color ?? '#6366f1',
        ]);

        // Attribution des permissions
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        // Nettoyer le cache des permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect('/admin/roles')
            ->with('success', 'Rôle « ' . $role->display_name . ' » créé avec succès.');
    }

    /** JSON pour l'édition inline AJAX */
    public function editData(Role $role)
    {
        abort_unless(
            request()->ajax() || request()->wantsJson(),
            404
        );

        return response()->json([
            'id'           => $role->id,
            'name'         => $role->name,
            'display_name' => $role->display_name ?? '',
            'description'  => $role->description ?? '',
            'color'        => $role->color ?? '#6366f1',
            'permissions'  => $role->permissions->pluck('name')->toArray(),
        ]);
    }

    /** Mettre à jour un rôle */
    public function update(Request $request, Role $role)
    {
        if ($role->name === 'super-admin') {
            return redirect('/admin/roles')
                ->with('error', 'Le rôle Super Admin ne peut pas être modifié.');
        }

        $request->validate([
            'name'          => ['required', 'string', 'max:255', 'regex:/^[a-z][a-z0-9\-]*$/', 'unique:roles,name,' . $role->id],
            'display_name'  => ['required', 'string', 'max:255'],
            'description'   => ['nullable', 'string'],
            'color'         => ['nullable', 'string'],
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ]);

        $role->update([
            'name'         => $request->name,
            'display_name' => $request->display_name,
            'description'  => $request->description,
            'color'        => $request->color ?? '#6366f1',
        ]);

        $role->syncPermissions($request->permissions ?? []);

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect('/admin/roles')
            ->with('success', 'Rôle « ' . $role->display_name . ' » mis à jour.');
    }

    /** Supprimer un rôle */
    public function destroy(Role $role)
    {
        if (in_array($role->name, ['super-admin', 'admin'])) {
            return redirect('/admin/roles')
                ->with('error', 'Ce rôle système ne peut pas être supprimé.');
        }

        $usersCount = $role->users()->count();
        if ($usersCount > 0) {
            return redirect('/admin/roles')
                ->with('error', "Impossible : {$usersCount} utilisateur(s) ont encore ce rôle.");
        }

        $label = $role->display_name ?? $role->name;
        $role->delete();

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect('/admin/roles')
            ->with('success', 'Rôle « ' . $label . ' » supprimé.');
    }
}
