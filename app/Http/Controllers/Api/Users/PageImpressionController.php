<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PageImpression;

class PageImpressionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'page_name' => 'required|string'
        ]);

        $page = PageImpression::firstOrCreate(
            [
                'page_name' => $request->page_name
            ],
            [
                'impression_count' => 0
            ]
        );

        $page->increment('impression_count');

        return $this->successResponse(
            'Page impression recorded successfully',
            [
                'page_name' => $page->page_name,
                'impression_count' => $page->fresh()->impression_count,
            ]
        );
    }
}