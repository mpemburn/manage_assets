<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminController extends Controller
{
    public function index()
    {
        $auth = new AuthService();
        $permissions = Permission::all();

        return view('permissions.index')
            ->with('ajaxUrl', '/api/create_permission')
            ->with('permissions', $permissions)
            ->with('token', $auth->getAuthToken());
    }
}
