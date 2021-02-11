<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Exceptions\RoleAlreadyExists;
use Spatie\Permission\Exceptions\RoleDoesNotExist;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Contracts\Role as RoleContract;

class RolesService
{
    public const ROLE_DOES_NOT_EXIST_ERROR = 'This Role does not exist';

    protected string $errorMessage;

    public function createRole(Request $request): JsonResponse
    {
        $roleName = $request->get('name');
        if (!$roleName) {
            return response()->json(['error' => 'Role Name cannot be empty.'], 404);
        }

        if (! $this->create($roleName)) {
            return response()->json(['error' => $this->errorMessage], 404);
        }
        return response()->json(['success' => true]);
    }

    public function updateRole(Request $request): JsonResponse
    {
        $role = $this->find($request);
        if (! $role) {
            return response()->json(['error' => self::ROLE_DOES_NOT_EXIST_ERROR], 404);
        }

        if (! $this->update($role, $request->get('name'))) {
            return response()->json(['error' => $this->errorMessage], 404);
        }

        return response()->json(['success' => true]);
    }

    public function deleteRole(Request $request): JsonResponse
    {
        $role = $this->find($request);
        if (! $role) {
            return response()->json(['error' => self::ROLE_DOES_NOT_EXIST_ERROR], 404);
        }
        try {
            $role->delete();
        } catch (RoleAlreadyExists $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }

        return response()->json(['success' => true]);
    }

    protected function create(string $roleName): bool
    {
        try {
            Role::create([
                'name' => $roleName,
                'guard_name' => 'web'
            ]);
        } catch (RoleAlreadyExists $e) {
            $this->errorMessage = $e->getMessage();
            Log::debug($this->errorMessage);

            return false;
        }

        return true;
    }

    protected function update(RoleContract $role, string $newName): bool
    {
        try {
            $role->name = $newName;
            $role->save();
        } catch (RoleAlreadyExists $e) {
            $this->errorMessage = $e->getMessage();
            Log::debug($this->errorMessage);

            return false;
        }

        return true;
    }

    protected function find(Request $request): ?RoleContract
    {
        $role = null;
        $roleId = $request->get('role_id');

        try {
            $role = Role::findById($roleId, 'web');
        } catch (RoleDoesNotExist $e) {
            $this->errorMessage = $e->getMessage();
            Log::debug($this->errorMessage);

            return null;
        }

        return  $role;
    }
}
