<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Like;

class LikeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'link_id' => 'required|string'
        ]);

        Like::create([
            'link_id' => $request->link_id
        ]);

        return $this->successResponse(
            'Liked successfully',
            null
        );
    }

    public function dislike(Request $request)
    {
        $request->validate([
            'link_id' => 'required|string'
        ]);

        $like = Like::where('link_id', $request->link_id)->first();

        if ($like) {
            $like->delete();

            return $this->successResponse(
                'Disliked successfully',
                null
            );
        }

        return $this->errorResponse(
            'No likes to remove',
            404
        );
    }

    public function count($link_id)
    {
        $count = Like::where('link_id', $link_id)->count();

        return $this->successResponse(
            'Like count fetched successfully',
            [
                'count' => $count,
            ]
        );
    }
}