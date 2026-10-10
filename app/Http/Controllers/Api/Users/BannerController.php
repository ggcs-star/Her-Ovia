<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Banner::where('status', 1)
                ->where(function($q) {
                    $q->whereNull('start_date')
                      ->orWhere('start_date', '<=', now());
                })
                ->where(function($q) {
                    $q->whereNull('end_date')
                      ->orWhere('end_date', '>=', now());
                });

            if ($request->has('page') && in_array($request->page, ['home', 'category', 'product'])) {
                $query->where('page', $request->page);
            }

            if ($request->has('position') && in_array($request->position, ['hero', 'mid', 'bottom'])) {
                $query->where('position', $request->position);
            }

            $banners = $query->orderBy('sort_order')->get();

            return $this->successResponse(
                'Banners fetched successfully',
                $banners
            );

        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }

    public function show($id)
    {
        try {
            $banner = Banner::where('id', $id)
                ->where('status', 1)
                ->where(function($q) {
                    $q->whereNull('start_date')
                      ->orWhere('start_date', '<=', now());
                })
                ->where(function($q) {
                    $q->whereNull('end_date')
                      ->orWhere('end_date', '>=', now());
                })
                ->first();

            if (!$banner) {
                return $this->errorResponse('Banner not found', 404);
            }

            return $this->successResponse(
                'Banner fetched successfully',
                $banner
            );
        } catch (\Exception $e) {
           return $this->errorResponse();
        }
    }

    public function getByPage($page)
    {
        try {
            if (!in_array($page, ['home', 'category', 'product'])) {
                return $this->errorResponse('Invalid page type', 400);
            }

            $banners = Banner::where('status', 1)
                ->where('page', $page)
                ->where(function($q) {
                    $q->whereNull('start_date')
                      ->orWhere('start_date', '<=', now());
                })
                ->where(function($q) {
                    $q->whereNull('end_date')
                      ->orWhere('end_date', '>=', now());
                })
                ->orderBy('sort_order')
                ->get();

            return $this->successResponse(
                "Banners for {$page} page fetched successfully",
                $banners
            );

        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }

    public function getByPosition($position)
    {
        try {
            if (!in_array($position, ['hero', 'mid', 'bottom'])) {
                return $this->errorResponse('Invalid position type', 400);
            }

            $banners = Banner::where('status', 1)
                ->where('position', $position)
                ->where(function($q) {
                    $q->whereNull('start_date')
                      ->orWhere('start_date', '<=', now());
                })
                ->where(function($q) {
                    $q->whereNull('end_date')
                      ->orWhere('end_date', '>=', now());
                })
                ->orderBy('sort_order')
                ->get();

            return $this->successResponse(
                "Banners for {$position} position fetched successfully",
                $banners
            );

        } catch (\Exception $e) {
            return $this->errorResponse();
        }
    }
}