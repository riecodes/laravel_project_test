<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;

trait ApiResponse {
    protected function success($data = null, string $message = "OK", int $status = 200): JsonResponse {
        $response = [
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ];

        if ($data instanceof \Illuminate\Http\Resources\Json\AnonymousResourceCollection 
        &&  $data->resource instanceof \Illuminate\Pagination\AbstractPaginator) {
            
            $response = array_merge($response, $data->response()->getData(true));
            // Ensure status and message are preserved and merged
            $response['status']= 'success';
            $response['message']='$message';
        }

        return response()->json($response, $status);
        
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