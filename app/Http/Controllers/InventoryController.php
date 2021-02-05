<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use App\Services\InventoryService;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $auth = new AuthService();
        $inventoryService = new InventoryService();

        return view('inventory', [
            'token' => $auth->getAuthToken(),
            'action' => '/api/receive_inventory',
            'headers' => InventoryService::INVENTORY_LIST_HEADER,
            'rows' => $inventoryService->getInventoryRows(),
        ]);
    }

    public function receive(Request $request): void
    {
        $inventoryService = new InventoryService();
        $inventoryService->receiveUploadedInventory($request->uploads);
    }

}
