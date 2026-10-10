<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

class OrganizationController extends Controller
{
    public function footerDetails(): JsonResponse
    {
        try {

            $organization = Organization::query()
                ->where('is_active', true)
                ->first();

            if (!$organization) {
                return $this->errorResponse(
                    'Organization not found',
                    404
                );
            }

            return $this->successResponse(
                'Organization details fetched successfully',
                [
                    'email'          => $organization->email,
                    'mobile'         => $organization->mobile,
                    'website'        => $organization->website,
                    'address'        => $organization->address,
                    'city'           => $organization->city,
                    'state'          => $organization->state,
                    'country'        => $organization->country,
                    'pincode'        => $organization->pincode,
                    'business_hours' => $organization->business_hours,
                ]
            );

        } catch (Throwable $e) {

            Log::error('Footer Organization API Error', [
                'message' => $e->getMessage(),
            ]);

            return $this->errorResponse();
        }
    }
}