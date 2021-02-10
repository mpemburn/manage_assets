<?php

namespace App\Http\Controllers;

use App\Services\PermissionsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Exceptions\PermissionAlreadyExists;
use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller
{
    protected PermissionsService $permissionsService;

    public function __construct(PermissionsService $permissionsService)
    {
        $this->permissionsService = $permissionsService;
    }

    public function create(Request $request)
    {
        return $this->permissionsService->createPermission($request);
    }
}
