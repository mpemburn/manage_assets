<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PermissionsCrudService
{

    protected string $errorMessage;

    protected function handleValidation(Request $request, array $rules): bool
    {
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $this->errorMessage = $validator->errors()->first();

            return false;
        }

        return true;
    }

    protected function hasError(): bool
    {
        return !empty($this->errorMessage);
    }

    public function create(Request $request, Model $model): JsonResponse
    {
        if ($this->handleValidation($request, [
            'name' => ['required', 'unique:' . $model->getTable(), 'max:255']
        ])) {
            $name = $request->get('name');
            try {
                $model->name = $name;
                $model->guard_name = 'web';
                $model->save();
            } catch (\Exception $e) {
                $this->errorMessage = $e->getMessage();
                Log::debug($this->errorMessage);
            }
        }

        if ($this->hasError()) {
            return response()->json(['error' => $this->errorMessage], 400);
        }

        return response()->json(['success' => true]);
    }

    public function update(Request $request, Model $model): JsonResponse
    {
        if ($this->handleValidation($request, [
            'name' => ['required', 'unique:' . $model->getTable(), 'max:255']
        ])) {
            $model = $this->find($request, $model);
            if (! $model) {
                return response()->json(['error' => $this->errorMessage], 400);
            }
            try {
                $model->update([
                    'name' => $request->get('name'),
                    'guard_name' => 'web'
                ]);
                $model->save();
            } catch (\Exception $e) {
                $this->errorMessage = $e->getMessage();
                Log::debug($this->errorMessage);
            }
        }

        if ($this->hasError()) {
            return response()->json(['error' => $this->errorMessage], 400);
        }

        return response()->json(['success' => true]);
    }

    public function delete(Request $request, Model $model): JsonResponse
    {
        $model = $this->find($request, $model);
        if (!$model) {
            return response()->json(['error' => $this->errorMessage], 400);
        }
        try {
            $model->delete();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        return response()->json(['success' => true]);
    }


    protected function find(Request $request, Model $model): ?Model
    {
        $modelId = $request->get('id');

        Log::debug($modelId);
        try {
            $model = $model->findById($modelId, 'web');
        } catch (\Exception $e) {
            $this->errorMessage = $e->getMessage();
            Log::debug($this->errorMessage);

            return null;
        }

        return $model;
    }
}
