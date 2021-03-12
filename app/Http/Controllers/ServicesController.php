<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Services\ServicesCrudService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    protected ServicesCrudService $crudService;

    public function __construct(ServicesCrudService $crudService)
    {
        $this->crudService = $crudService;
    }

    public function create(Request $request): JsonResponse
    {
        return $this->crudService->create($request);
    }

    public function update(Request $request, int $serviceId): JsonResponse
    {
        return $this->crudService->update($request, $serviceId);
    }

    public function delete(Request $request, int $serviceId): JsonResponse
    {
        return $this->crudService->delete($request, $serviceId);
    }
}
