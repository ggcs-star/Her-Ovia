<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Helpers\S3Helper;
class ProfileController extends Controller
{
    public function show(): JsonResponse
    {
        try {
            $user = auth()->user();

            return $this->successResponse(
                'Profile fetched successfully',
                [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->mobile,
                    'profile_image' => $user->profile_image,
                    'created_at' => $user->created_at?->toISOString(),
                ]
            );

        } catch (Throwable $e) {

            Log::error('Get User Profile API Error', [
                'message' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return $this->errorResponse();
        }
    }

    public function update(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'          => 'nullable|string|max:255',
            'mobile'        => 'nullable|string|max:20',
            'address'       => 'nullable|string|max:500',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        try {
            $user = $request->user();
            $updateData = [];

            if (isset($data['name'])) {
                $updateData['name'] = $data['name'];
            }

            if (isset($data['mobile'])) {
                $updateData['mobile'] = $data['mobile'];
            }

            if (isset($data['address'])) {
                $updateData['address'] = $data['address'];
            }

            if ($request->hasFile('profile_image')) {
                $updateData['profile_image'] = $this->handleAvatar(
                    $request->file('profile_image'),
                    $user
                );
            }

            $user->update($updateData);
            $user->refresh();

            return $this->successResponse(
                'Profile updated successfully',
                [
                    'id'            => $user->id,
                    'name'          => $user->name,
                    'email'         => $user->email,
                    'mobile'        => $user->mobile,
                    'address'       => $user->address,
                    'profile_image' => $user->profile_image,
                    'created_at'    => $user->created_at?->toISOString(),
                ]
            );

        } catch (Throwable $e) {

            Log::error('Update User Profile API Error', [
                'message' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return $this->errorResponse();
        }
    }
    
    private function handleAvatar($file, $user): string
    {
        Log::info('Handling avatar upload', [
            'user_id' => $user->id,
            'file_name' => $file->getClientOriginalName()
        ]);

        if ($user->profile_image) {
            try {
                $oldPath = str_replace(S3Helper::url(''), '', $user->profile_image);
                if ($oldPath) {
                    S3Helper::delete($oldPath);
                }
            } catch (\Exception $e) {
                Log::warning('Failed to delete old image', ['error' => $e->getMessage()]);
            }
        }

        $path = 'admin/profile/user_' . $user->id;
        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        try {
        $uploaded = S3Helper::storeAs(
                $file,
                $path,
                $fileName
            );
            
                if (!$uploaded) {
                $uploaded = S3Helper::storeAs(
                    $file,
                    $path,
                    $fileName,
                    'public'
                );
            }

            if (!$uploaded) {
                $uploaded = S3Helper::storeAs(
                    $file,
                    $path,
                    $fileName,
                    [
                        'visibility' => 'public',
                        'ContentType' => $file->getMimeType()
                    ]
                );
            }
            
            if (!$uploaded) {
                throw new \Exception('S3 upload failed - no path returned');
            }

            $url = S3Helper::url($uploaded);
            
            Log::info('S3 upload success', [
                'path' => $uploaded,
                'url' => $url
            ]);
            
            return $uploaded;

        } catch (\Exception $e) {
            Log::error('S3 upload failed', [
                'error' => $e->getMessage(),
                'path' => $path,
                'file' => $fileName
            ]);
            throw $e;
        }
    }
    public function removeImage(): JsonResponse
    {
        try {
            $user = auth()->user();
            
            if ($user->profile_image) {
                S3Helper::delete($user->profile_image);
                
                $user->profile_image = null;
                $user->save();
            }
            
            return $this->successResponse(
                'Profile image removed successfully',
                [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'mobile' => $user->mobile,
                    'profile_image' => null,
                ]
            );
            
        } catch (Throwable $e) {
            Log::error('Remove profile image error', [
                'message' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            
            return $this->errorResponse();
        }
    }

}
