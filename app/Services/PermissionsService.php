<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Exceptions\PermissionAlreadyExists;
use Spatie\Permission\Models\Permission;

class PermissionsService
{
    protected string $errorMessage;

    public function createPermission(Request $request): JsonResponse
    {
        $truth = collect();

        $permissionName = $request->get('name');
        if (!$permissionName) {
            return response()->json(['error' => 'Permission Name cannot be empty.'], 404);
        }

        $context = $request->get('context');
        switch ($context) {
            case 'web':
            case 'api':
                $truth->push($this->create($permissionName, $context));
                break;
            case 'both':
                $truth->push($this->create($permissionName, 'web'));
                $truth->push($this->create($permissionName, 'api'));
                break;
        }

        // If the $truth Collection contains any false values, return an error response
        if ($truth->contains(static function ($value) { return ! $value; })) {
            return response()->json(['error' => $this->errorMessage], 404);
        }
        return response()->json(['success' => true]);
    }

    protected function create(string $permissionName, string $guardName): bool
    {
        try {
            Permission::create([
                'name' => $permissionName,
                'guard_name' => $guardName
            ]);
        } catch (PermissionAlreadyExists $e) {
            $this->errorMessage = $e->getMessage();
            Log::debug($this->errorMessage);

            return false;
        }

        return true;
    }
}
