<?php

namespace App\Http\Controllers;

use App\Services\PermissionsCrudService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    protected PermissionsCrudService $crudService;

    public function __construct(PermissionsCrudService $rolesService)
    {
        $this->crudService = $rolesService;
    }

    public function create(Request $request): JsonResponse
    {
        return $this->crudService->create($request, new Role());
    }

    public function update(Request $request): JsonResponse
    {
        return $this->crudService->update($request, new Role());
    }

    public function delete(Request $request): JsonResponse
    {
        return $this->crudService->delete($request, new Role());
    }

    public function getPermissions(Request $request): JsonResponse
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
