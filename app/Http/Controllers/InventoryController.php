<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use App\Services\InventoryService;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InventoryController extends Controller
{
    public function index()
    {
        $auth = new AuthService();

        return view('inventory', [
            'token' => $auth->getAuthToken(),
            'action' => '/api/receive_inventory',
        ]);
    }

    public function receive(Request $request): void
    {
        Log::debug('Got it');
        $inventoryService = new InventoryService();
        $inventoryService->receiveUploadedInventory($request->uploads);
    }

}
