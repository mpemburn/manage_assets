<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function permissions()
    {
        $permissions = Permission::all();

        return view('permissions.index')
            ->with('baseUrl', '/api/permissions/')
            ->with('permissions', $permissions)
            ->with('token', $this->authService->getAuthToken());
    }
}
