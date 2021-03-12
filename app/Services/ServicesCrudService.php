<?php

namespace App\Services;

use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ServicesCrudService
{
    protected ValidationService $validator;

    public function __construct(ValidationService $validationService)
    {
        $this->validator = $validationService;
    }

    public function create(Request $request): JsonResponse
    {
        if ($this->validator->handle($request, [
            'name' => ['required', 'unique:services', 'max:255'],
            'username' => ['required', 'email'],
            'password' => ['required', 'min:8', 'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/'],
        ], [
            'password.regex' => 'Password field must contain at least one number and both uppercase and lowercase letters.'
        ])) {
            try {
                $service = new Service($request->all());
                $service->save();
            } catch (\Exception $e) {
                $this->validator->addError($e->getMessage());
                Log::debug($e->getMessage());
            }
        }

        if ($this->validator->hasError()) {
            return response()->json(['error' => $this->validator->getMessage()], 400);
        }

        return response()->json(['success' => true]);

    }
}
