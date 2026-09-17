<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Services\ShiprocketService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class ShippingController extends Controller
{
    public function check(
        Request $request,
        ShiprocketService $shiprocket
    ): JsonResponse {
        $validated = $request->validate([
            'pincode' => [
                'required',
                'digits:6',
            ],
            'cod' => [
                'nullable',
                'boolean',
            ],
        ]);

        try {
            $result = $shiprocket->checkServiceability(
                $validated['pincode'],
                (bool) ($validated['cod'] ?? false)
            );

            return response()->json([
                'success' => true,
                'data' => $result,
            ]);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Unable to check delivery availability right now.',
            ], 500);
        }
    }
}