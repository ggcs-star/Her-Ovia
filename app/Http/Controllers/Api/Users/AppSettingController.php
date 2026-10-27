<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

class AppSettingController extends Controller
{
    public function index(): JsonResponse
    {
        try {

            $setting = Cache::remember(
                'app_settings',
                3600,
                function () {
                    return AppSetting::query()
                        ->where('is_active', true)
                        ->latest()
                        ->first();
                }
            );

            if (!$setting) {
                return $this->errorResponse('App settings not found', 404);
            }

            return $this->successResponse(
                'App settings fetched successfully',
                [
                    'app_name' => $setting->app_name,

                    'app_logo' => $setting->app_logo_url,
                    'splash_logo' => $setting->splash_logo_url,
                    'header_logo' => $setting->header_logo_url,

                    'is_active' => $setting->is_active,
                ]
            );

        } catch (Throwable $e) {

            Log::error('App Setting API Error', [
                'message' => $e->getMessage(),
            ]);

            return $this->errorResponse();
        }
    }
}