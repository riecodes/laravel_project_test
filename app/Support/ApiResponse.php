<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;

trait ApiResponse {
    protected function success($data = null, string $message = "OK", int $status = 200): JsonResponse {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    protected function created($data = null, string $message = "Created"): JsonResponse {
        return $this->success($data, $message, 201);
    }

    protected function updated($data = null, string $message = "Updated"): JsonResponse {
        return $this->success($data, $message, 200);
    }

    protected function deleted(string $message = "Deleted"): JsonResponse {
        return $this->success(null, $message, 200);
    }

    protected function error(string $message = 'Error', int $status = 400, $errors = null): JsonResponse {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }


}