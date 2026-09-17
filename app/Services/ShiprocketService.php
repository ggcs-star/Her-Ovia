<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class ShiprocketService
{
    private string $baseUrl;
    private string $email;
    private string $password;
    private string $pickupPincode;
    private float $defaultWeight;

    public function __construct()
    {
        $this->baseUrl = rtrim(
            config('services.shiprocket.base_url'),
            '/'
        );

        $this->email = config('services.shiprocket.email');
        $this->password = config('services.shiprocket.password');
        $this->pickupPincode = config('services.shiprocket.pickup_pincode');
        $this->defaultWeight = (float) config(
            'services.shiprocket.default_weight',
            0.5
        );
    }

    /**
     * Get Shiprocket authentication token.
     */
    private function getToken(): string
    {
        return Cache::remember(
            'shiprocket_api_token',
            now()->addDays(9),
            function () {
                $response = Http::timeout(20)
                    ->acceptJson()
                    ->post(
                        $this->baseUrl . '/v1/external/auth/login',
                        [
                            'email' => $this->email,
                            'password' => $this->password,
                        ]
                    );

                if (!$response->successful()) {
                    \Log::error('Shiprocket authentication failed', [
                        'status' => $response->status(),
                        'response' => $response->json() ?: $response->body(),
                    ]);

                    throw new RuntimeException(
                        'Unable to authenticate with Shiprocket.'
                    );
                }

                $data = $response->json();

                if (empty($data['token'])) {
                    throw new RuntimeException(
                        'Shiprocket authentication token was not returned.'
                    );
                }

                return $data['token'];
            }
        );
    }
    public function checkServiceability(
        string $deliveryPincode,
        bool $cod = false,
        ?float $weight = null
    ): array {
        $deliveryPincode = trim($deliveryPincode);

        if (!preg_match('/^\d{6}$/', $deliveryPincode)) {
            throw new RuntimeException(
                'Invalid delivery pincode.'
            );
        }

        if (
            !$this->pickupPincode ||
            !preg_match('/^\d{6}$/', $this->pickupPincode)
        ) {
            throw new RuntimeException(
                'Shiprocket pickup pincode is not configured.'
            );
        }

        $weight = $weight && $weight > 0
            ? $weight
            : $this->defaultWeight;

        $token = $this->getToken();

        $response = Http::timeout(20)
            ->acceptJson()
            ->withToken($token)
            ->get(
                $this->baseUrl . '/v1/external/courier/serviceability/',
                [
                    'pickup_postcode' => $this->pickupPincode,
                    'delivery_postcode' => $deliveryPincode,
                    'weight' => $weight,
                    'cod' => $cod ? 1 : 0,
                ]
            );

        if (!$response->successful()) {
            throw new RuntimeException(
                'Unable to check Shiprocket serviceability.'
            );
        }

        $data = $response->json();

        $couriers = data_get(
            $data,
            'data.available_courier_companies',
            []
        );

        if (!is_array($couriers) || empty($couriers)) {
            return [
                'serviceable' => false,
                'delivery_pincode' => $deliveryPincode,
                'pickup_pincode' => $this->pickupPincode,
                'weight' => $weight,
                'couriers' => [],
                'shipping' => null,
                'delivery_date' => null,
            ];
        }
        $normalizedCouriers = collect($couriers)
            ->map(function ($courier) {
                return [
                    'courier_id' => $courier['courier_company_id'] ?? null,
                    'courier_name' => $courier['courier_name'] ?? null,
                    'rate' => isset($courier['rate'])
                        ? (float) $courier['rate']
                        : null,
                    'freight_charge' => isset($courier['freight_charge'])
                        ? (float) $courier['freight_charge']
                        : null,
                    'estimated_delivery_days' =>
                        $courier['estimated_delivery_days'] ?? null,
                    'etd' => $courier['etd'] ?? null,
                ];
            })
            ->values()
            ->all();
        $recommended = collect($normalizedCouriers)
            ->filter(function ($courier) {
                return $courier['rate'] !== null
                    && $courier['rate'] >= 0;
            })
            ->sortBy('rate')
            ->first();

        if (!$recommended) {
            return [
                'serviceable' => true,
                'delivery_pincode' => $deliveryPincode,
                'pickup_pincode' => $this->pickupPincode,
                'weight' => $weight,
                'couriers' => $normalizedCouriers,
                'shipping' => null,
                'delivery_date' => null,
            ];
        }

        return [
            'serviceable' => true,
            'delivery_pincode' => $deliveryPincode,
            'pickup_pincode' => $this->pickupPincode,
            'weight' => $weight,
            'shipping' => round((float) $recommended['rate'], 2),
            'delivery_date' => $recommended['etd'],
            'estimated_delivery_days' =>
                $recommended['estimated_delivery_days'],
            'courier' => [
                'id' => $recommended['courier_id'],
                'name' => $recommended['courier_name'],
            ],
            'couriers' => $normalizedCouriers,
        ];
    }
}