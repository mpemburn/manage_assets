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
        /** @var User $user */
        $user = User::find($userId);

        Log::debug($request->all());
        $currentUserRoles = $this->getCurrentUserRoles($user);
        $rolesFromEditor = $this->getRolesFromEditor($request);

//        $currentUserPermissions = $this->getCurrentUserPermissions($user);
//        $permissionsFromEditor = $this->getPermissionsFromEditor($request);
//
//
//        $toBeAdded = $permissionsFromEditor->diff($currentUserPermissions);
//        $toBeRemoved = $currentUserPermissions->diff($permissionsFromEditor);
//
//        if ($this->hasError()) {
//            return response()->json(['error' => $this->errorMessage], 400);
//        }

        return response()->json(['success' => true]);
    }

    protected function getCurrentUserRoles(User $user): Collection
    {
        return $user->roles()->pluck('name');
    }

    protected function getRolesFromEditor(Request $request): Collection
    {
        return collect($request->all())->keys()
            ->reject('user_id')
            ->map(static function($item) {
                return str_replace('_', ' ', $item);
            });
    }

    protected function getCurrentUserPermissions(User $user): Collection
    {
        return $user->getAllPermissions()->map(static function (Permission $item) {
            return $item->name;
        });
    }

    protected function getPermissionsFromEditor(Request $request): Collection
    {
        return collect($request->all())->keys()
            ->reject('user_id')
            ->map(static function($item) {
                return str_replace('_', ' ', $item);
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
