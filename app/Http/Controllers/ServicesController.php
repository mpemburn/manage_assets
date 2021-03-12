<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Services\ServicesCrudService;
use App\Services\ValidationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
}
