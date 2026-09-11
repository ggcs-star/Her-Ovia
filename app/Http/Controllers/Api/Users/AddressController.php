<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class AddressController extends Controller
{

    public function index(): JsonResponse
    {
        try {
            $user = auth()->user();

            $addresses = UserAddress::where('user_id', $user->id)
                ->orderByDesc('is_default')
                ->latest()
                ->get()
                ->map(fn($address) => $this->formatAddress($address));

            return response()->json([
                'success' => true,
                'data' => [
                    'addresses' => $addresses,
                    'total' => $addresses->count(),
                ]
            ]);
        } catch (Throwable $e) {
            $this->logError('Get Address', $e);
            return $this->errorResponse('Unable to fetch addresses');
        }
    }
    public function show(int $addressId): JsonResponse
    {
        try {
            $user = auth()->user();

            $address = $this->findUserAddress($addressId, $user->id);

            return response()->json([
                'success' => true,
                'data' => $this->formatAddress($address)
            ]);

        } catch (Throwable $e) {
            $this->logError('Get Address', $e, auth()->id(), $addressId);

            return $this->errorResponse('Unable to fetch address');
        }
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validateAddress($request);

        try {
            DB::beginTransaction();

            $user = $request->user();

            if ($data['is_default']) {
                $this->unsetDefaultAddress($user->id, $data['type']);
            }

            $address = UserAddress::create([
                'user_id' => $user->id,
                ...$data
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Address added successfully',
                'data' => [
                    'address' => $this->formatAddress($address)
                ]
            ], 201);

        } catch (Throwable $e) {
            DB::rollBack();
            $this->logError('Add Address', $e, $request->user()?->id);
            return $this->errorResponse('Unable to add address');
        }
    }


    public function update(Request $request, int $addressId): JsonResponse
    {
        $data = $this->validateAddress($request);

        try {
            DB::beginTransaction();

            $user = $request->user();
            $address = $this->findUserAddress($addressId, $user->id);

            if ($data['is_default']) {
                $this->unsetDefaultAddress($user->id, $data['type'], $address->id);
            }

            $address->update($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Address updated successfully',
                'data' => [
                    'address' => $this->formatAddress($address)
                ]
            ]);

        } catch (Throwable $e) {
            DB::rollBack();
            $this->logError('Update Address', $e, $request->user()?->id, $addressId);
            return $this->errorResponse('Unable to update address');
        }
    }


    public function destroy(int $addressId): JsonResponse
    {
        try {
            DB::beginTransaction();

            $user = auth()->user();
            $address = $this->findUserAddress($addressId, $user->id);

            $wasDefault = $address->is_default;
            $type = $address->type;

            $address->delete();

            if ($wasDefault) {
                UserAddress::where('user_id', $user->id)
                    ->where('type', $type)
                    ->orderBy('id')
                    ->limit(1)
                    ->update(['is_default' => true]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Address deleted successfully'
            ]);

        } catch (Throwable $e) {
            DB::rollBack();
            $this->logError('Delete Address', $e, auth()->id(), $addressId);
            return $this->errorResponse('Unable to delete address');
        }
    }



    private function validateAddress(Request $request): array
    {
        return $request->validate([
            'type' => 'required|in:shipping,billing',
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'is_default' => 'boolean',
        ]);
    }

    private function findUserAddress(int $addressId, int $userId): UserAddress
    {
        $address = UserAddress::where('id', $addressId)
            ->where('user_id', $userId)
            ->first();

        if (!$address) {
            abort(response()->json([
                'success' => false,
                'message' => 'Address not found'
            ], 404));
        }

        return $address;
    }

    private function unsetDefaultAddress(int $userId, string $type, ?int $exceptId = null): void
    {
        UserAddress::where('user_id', $userId)
            ->where('type', $type)
            ->when($exceptId, fn($q) => $q->where('id', '!=', $exceptId))
            ->update(['is_default' => false]);
    }

    private function formatAddress(UserAddress $address): array
    {
        return [
            'id' => $address->id,
            'type' => $address->type,
            'full_name' => $address->full_name,
            'phone' => $address->phone,
            'address_line_1' => $address->address_line_1,
            'address_line_2' => $address->address_line_2,
            'city' => $address->city,
            'state' => $address->state,
            'postal_code' => $address->postal_code,
            'country' => $address->country,
            'is_default' => $address->is_default,
        ];
    }

    private function logError(string $context, Throwable $e, $userId = null, $addressId = null): void
    {
        Log::error("{$context} API Error", [
            'message' => $e->getMessage(),
            'user_id' => $userId,
            'address_id' => $addressId,
        ]);
    }

    private function errorResponse(string $message): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message
        ], 500);
    }
    public function setDefault($addressId): JsonResponse
    {
        try {
            DB::beginTransaction();

            $user = auth()->user();
            $address = UserAddress::where('id', $addressId)
                ->where('user_id', $user->id)
                ->first();

            if (!$address) {
                return response()->json([
                    'success' => false,
                    'message' => 'Address not found'
                ], 404);
            }

            UserAddress::where('user_id', $user->id)
                ->where('type', $address->type)
                ->update(['is_default' => false]);

            $address->update(['is_default' => true]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Default address updated successfully',
                'data' => [
                    'address' => $this->formatAddress($address)
                ]
            ]);

        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Set Default Address Error', [
                'message' => $e->getMessage(),
                'user_id' => auth()->id(),
                'address_id' => $addressId
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Unable to set default address'
            ], 500);
        }
    }
}
