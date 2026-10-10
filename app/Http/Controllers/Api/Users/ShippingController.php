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

            return $this->successResponse(
                'Delivery availability fetched successfully',
                $result
            );
        
        } catch (Throwable $e) {
            report($e);

            if ($e->getMessage() === 'SHIPROCKET_CREDENTIALS_MISSING') {
                return $this->errorResponse(
                    'Shiprocket credentials are not configured.',
                    503
                );
            }

            return $this->errorResponse(
                'Something went wrong. Please try again later.',
                502
            );
        }

    }
}