<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Exceptions\PermissionAlreadyExists;
use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller
{
    public function create(Request $request)
    {
        try {
            Permission::create([
                'name' => $request->get('name'),
                'guard_name' => 'web'
            ]);
        } catch (PermissionAlreadyExists $e) {
            Log::debug($e->getMessage());
            return response()->json(['error' => $e->getMessage()], 404);
        }

    }
}
