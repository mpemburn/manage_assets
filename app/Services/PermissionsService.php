<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Exceptions\PermissionAlreadyExists;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Contracts\Permission as PermissionContract;

class PermissionsService
{
    public const PERMISSION_DOES_NOT_EXIST_ERROR = 'This Permission does not exist';

    protected string $errorMessage;

    public function createPermission(Request $request): JsonResponse
    {
        $permissionName = $request->get('name');
        if (!$permissionName) {
            return response()->json(['error' => 'Permission Name cannot be empty.'], 404);
        }

        if (! $this->create($permissionName)) {
            return response()->json(['error' => $this->errorMessage], 404);
        }
        return response()->json(['success' => true]);
    }

    public function updatePermission(Request $request): JsonResponse
    {
        $permission = $this->find($request);
        if (! $permission) {
            return response()->json(['error' => self::PERMISSION_DOES_NOT_EXIST_ERROR], 404);
        }

        if (! $this->update($permission, $request->get('name'))) {
            return response()->json(['error' => $this->errorMessage], 404);
        }

        return response()->json(['success' => true]);
    }

    public function deletePermission(Request $request): JsonResponse
    {
        $permission = $this->find($request);
        if (! $permission) {
            return response()->json(['error' => self::PERMISSION_DOES_NOT_EXIST_ERROR], 404);
        }
        try {
            $permission->delete();
        } catch (PermissionAlreadyExists $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }

        return response()->json(['success' => true]);
    }

    protected function create(string $permissionName): bool
    {
        try {
            Permission::create([
                'name' => $permissionName,
                'guard_name' => 'web'
            ]);
        } catch (PermissionAlreadyExists $e) {
            $this->errorMessage = $e->getMessage();
            Log::debug($this->errorMessage);

            return false;
        }

        return true;
    }

    protected function update(PermissionContract $permission, string $newName): bool
    {
        try {
            $permission->name = $newName;
            $permission->save();
        } catch (PermissionAlreadyExists $e) {
            $this->errorMessage = $e->getMessage();
            Log::debug($this->errorMessage);

            return false;
        }

        return true;
    }

    protected function find(Request $request): ?PermissionContract
    {
        $permission = null;
        $permissionId = $request->get('permission_id');

        try {
            $permission = Permission::findById($permissionId, 'web');
        } catch (PermissionDoesNotExist $e) {
            $this->errorMessage = $e->getMessage();
            Log::debug($this->errorMessage);

            return null;
        }

        return  $permission;
    }
}
