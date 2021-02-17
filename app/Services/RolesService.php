<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesService
{
    public function getPermissionsForRole(Request $request): JsonResponse
    {
        $roleName = $request->get('role_name');
        $role = Role::findByName($roleName, 'web');
        $permissions = [];
        $role->getAllPermissions()->each(static function (Permission $permission) use (&$permissions) {
            $permissions[] = $permission->name;
        });

        return response()->json(['success' => true, 'permissions' => $permissions]);
    }
}
