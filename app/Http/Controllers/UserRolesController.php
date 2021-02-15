<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserRolesController extends Controller
{
    public function edit(Request $request)
    {
        Log::debug('Works!');
    }
}
