<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;

class UserRolesService
{
    protected string $errorMessage = 'You were expecting maybe...';

    protected function hasError(): bool
    {
        return !empty($this->errorMessage);
    }

    public function edit(Request $request): JsonResponse
    {
        $userId = $request->get('user_id');
        $user = User::find($userId);

        $this->processRoles($user, $request);
        $this->processPermissions($user, $request);

        if ($this->hasError()) {
            return response()->json(['error' => $this->errorMessage], 400);
        }

        return response()->json(['success' => true]);
    }

    protected function processRoles(User $user, Request $request): void
    {
        $currentUserRoles = $this->getCurrentUserRoles($user);
        $rolesFromEditor = $this->getValuesFromEditorCheckboxes($request, 'role');

        $this->addRoles($user, $currentUserRoles, $rolesFromEditor);
        $this->removRoles($user, $currentUserRoles, $rolesFromEditor);
    }

    protected function processPermissions(User $user, Request $request): void
    {
        $currentUserPermissions = $this->getCurrentUserPermissions($user);
        $permissionsFromEditor = $this->getValuesFromEditorCheckboxes($request, 'permission');

        $this->addPermissions($user, $permissionsFromEditor, $currentUserPermissions);
        $this->removePermissions($user, $permissionsFromEditor, $currentUserPermissions);
    }

    protected function addRoles(User $user, $currentUserRoles, $rolesFromEditor): void
    {
        $toBeAdded = $rolesFromEditor->diff($currentUserRoles);
        if ($toBeAdded->isNotEmpty()) {
            $toBeAdded->values()->each(static function (string $role) use ($user) {
                $user->assignRole($role);
            });
        }
    }

    protected function removRoles(User $user, $currentUserRoles, $rolesFromEditor): void
    {
        $toBeRemoved = $currentUserRoles->diff($rolesFromEditor);
        if ($toBeRemoved->isNotEmpty()) {
            $toBeRemoved->values()->each(static function (string $role) use ($user) {
                $user->removeRole($role);
            });
        }
    }

    protected function addPermissions(User $user, $permissionsFromEditor, $currentUserPermissions): void
    {
        $toBeAdded = $permissionsFromEditor->diff($currentUserPermissions);
        if ($toBeAdded->isNotEmpty()) {
            $toBeAdded->values()->each(static function (string $permission) use ($user) {
                $user->givePermissionTo($permission);
            });
        }
    }

    protected function removePermissions(User $user, $permissionsFromEditor, $currentUserPermissions): void
    {
        $toBeRemoved = $currentUserPermissions->diff($permissionsFromEditor);
        if ($toBeRemoved->isNotEmpty()) {
            $toBeRemoved->values()->each(static function (string $permission) use ($user) {
                $user->revokePermissionTo($permission);
            });
        }
    }

    protected function getCurrentUserRoles(User $user): Collection
    {
        return $user->roles()->pluck('name');
    }

    protected function getValuesFromEditorCheckboxes(Request $request, string $entityType): Collection
    {
        return collect($request->get($entityType));
    }

    protected function getCurrentUserPermissions(User $user): Collection
    {
        return $user->getAllPermissions()->map(static function (Permission $item) {
            return $item->name;
        });
    }

    protected function handleValidation(Request $request, array $rules): bool
    {
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $this->errorMessage = $validator->errors()->first();

            return false;
        }

        return true;
    }


}
