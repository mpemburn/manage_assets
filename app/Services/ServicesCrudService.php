<?php

namespace App\Services;

use App\Models\Service;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ServicesCrudService
{
    protected const NAME_VALIDATION = [
        'name' => ['required', 'unique:services', 'max:255']
    ];

    protected const USERNAME_PASSWORD_VALIDATIONS = [
        'username' => ['required', 'email'],
        'password' => ['required', 'min:8', 'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/'],
    ];

    protected ValidationService $validator;

    public function __construct(ValidationService $validationService)
    {
        $this->validator = $validationService;
    }

    public function create(Request $request): JsonResponse
    {
        if ($this->validator->handle($request, array_merge(self::NAME_VALIDATION, self::USERNAME_PASSWORD_VALIDATIONS), [
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
    public function update(Request $request, int $serviceId): JsonResponse
    {
        if ($this->validator->handle($request, self::USERNAME_PASSWORD_VALIDATIONS, [
            'password.regex' => 'Password field must contain at least one number and both uppercase and lowercase letters.'
        ])) {
            try {
                $service = Service::find($serviceId);
                $service->update($request->all());
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

    public function delete(Request $request, int $serviceId): JsonResponse
    {
        try {
            $service = Service::find($serviceId);
            if (! $service) {
                throw new ModelNotFoundException("A Service with the ID of $serviceId was not found.");
            }
            $service->delete();
        } catch (\Exception $e) {
            $this->validator->addError($e->getMessage());
            Log::debug($e->getMessage());
        }

        if ($this->validator->hasError()) {
            return response()->json(['error' => $this->validator->getMessage()], 400);
        }

        return response()->json(['success' => true]);
    }
}
