<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function successResponse(
        string $message,
        mixed $data = [],
        int $status = 200,
        array $errors = []
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data ?? [],
            'errors' => $errors,
        ], $status);
    }

    protected function errorResponse(
        string $message = 'Something went wrong',
        int $status = 500,
        mixed $data = [],
        array $errors = []
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => $data ?? [],
            'errors' => $errors,
        ], $status);
    }
}