<?php

namespace App\Http\Controllers;

use App\Services\PermissionsCrudService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller
{
    protected PermissionsCrudService $permissionsService;

    public function __construct(PermissionsCrudService $permissionsService)
    {
        $this->permissionsService = $permissionsService;
    }

    public function create(Request $request): JsonResponse
    {
        return $this->permissionsService->createEntity($request, new Permission());
    }

    public function update(Request $request): JsonResponse
    {
        return $this->permissionsService->updatePermission($request);
    }

    public function delete(Request $request): JsonResponse
    {
        return $this->permissionsService->deletePermission($request);
    }
}
