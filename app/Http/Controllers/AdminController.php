<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function roles()
    {
        $roles = Role::all();

        return view('roles.index')
            ->with('action', '/api/roles/')
            ->with('roles', $roles)
            ->with('token', $this->authService->getAuthToken());
    }

    public function permissions()
    {
        $permissions = Permission::all();

        return view('permissions.index')
            ->with('action', '/api/permissions/')
            ->with('permissions', $permissions)
            ->with('token', $this->authService->getAuthToken());
    }


    public function userRoles()
    {

    }
}
