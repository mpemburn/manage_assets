<?php

namespace App\Http\Controllers;

use App\Services\RolesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    protected RolesService $rolesService;

    public function __construct(RolesService $rolesService)
    {
        $this->rolesService = $rolesService;
    }

    public function create(Request $request): JsonResponse
    {
        return $this->rolesService->createRole($request);
    }

    public function update(Request $request): JsonResponse
    {
        return $this->rolesService->updateRole($request);
    }

    public function delete(Request $request): JsonResponse
    {
        return $this->rolesService->deleteRole($request);
    }
}
