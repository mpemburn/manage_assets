<?php

namespace App\Http\Controllers;

use App\Services\PermissionsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PermissionsController extends Controller
{
    protected PermissionsService $permissionsService;

    public function __construct(PermissionsService $permissionsService)
    {
        $this->permissionsService = $permissionsService;
    }

    public function create(Request $request): JsonResponse
    {
        return $this->permissionsService->createPermission($request);
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
