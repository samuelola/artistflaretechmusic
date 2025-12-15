<?php

namespace App\Http\Middleware;

use App\Models\Permission;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;

class RolePermissionMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return Redirect::route('login')->with('error', 'Please log in to access this resource.');
        }

        $user = Auth::user();

        if (empty($user->role_id)) {
            return Redirect::back()->with('error', 'You do not have a role assigned.');
        }

        // Define Gates for permissions
        $this->defineGates();

        return $this->checkAccess($next, $request);
    }

    private function defineGates()
    {
        $permissions = Permission::all();

        foreach ($permissions as $permission) {
            Gate::define($permission->name, function (User $user) use ($permission) {
                return $user->hasPermissionRole($permission->name);
            });
        }
    }

    private function checkAccess($next, $request)
    {
        $routeName = $request->route()->getName();

        $permissionMap = [
            'dashboard' => 'Access Dashboard',
            'users.index' => 'Access User',
            'users.create' => 'Create Users',
            'users.store' => 'Create Users',
            'users.edit' => 'Edit User',
            'users.update' => 'Edit User',
            'users.destroy' => 'Delete User',
            'roles.index' => 'Access Role',
            'roles.create' => 'Create Role',
            'roles.store' => 'Create Role',
            'roles.edit' => 'Edit Role',
            'roles.update' => 'Edit Role',
            'roles.destroy' => 'Delete Role',
            'roles.permissionEdit' => 'Assigned Permission',
            'roles.permissionUpdate' => 'Assigned Permission',
            'modules.index' => 'Access Module',
            'modules.create' => 'Create Module',
            'modules.store' => 'Create Module',
            'modules.edit' => 'Edit Module',
            'modules.update' => 'Edit Module',
            'modules.destroy' => 'Delete Module',
            'permissions.index' => 'Access Permission',
            'permissions.create' => 'Create Permission',
            'permissions.store' => 'Create Permission',
            'permissions.edit' => 'Edit Permission',
            'permissions.update' => 'Edit Permission',
            'permissions.destroy' => 'Delete Permission'
        ];

        if (!isset($permissionMap[$routeName]) || !Gate::allows($permissionMap[$routeName])) {
            return Redirect::back()->with('error', 'You do not have permission to access this resource.');
        }

        return $next($request);
    }
}