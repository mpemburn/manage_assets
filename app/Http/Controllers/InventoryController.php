<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use App\Services\InventoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    protected AuthService $authService;
    protected InventoryService $inventoryService;

    public function __construct(AuthService $authService, InventoryService $inventoryService)
    {
        $this->authService = $authService;
        $this->inventoryService = $inventoryService;
    }

    public function index()
    {
        return view('inventory', [
            'token' => $this->authService->getAuthToken(),
            'action' => '/api/receive_inventory',
            'headers' => InventoryService::INVENTORY_LIST_HEADER,
            'rows' => $this->inventoryService->getInventoryRows(),
        ]);
    }

    public function receive(Request $request): JsonResponse
    {
        return $this->inventoryService->receiveUploadedInventory($request->uploads);
    }

}
