<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'link_id' => 'required|string',
            'username' => 'required|string|max:50',
            'comment' => 'required|string'
        ]);

        $comment = Comment::create([
            'link_id' => $request->link_id,
            'username' => $request->username,
            'comment' => $request->comment
        ]);

        return $this->successResponse(
            'Comment added successfully',
            $comment
        );
    }

    public function index($link_id)
    {
        $comments = Comment::where('link_id', $link_id)
            ->latest()
            ->get();

        return $this->successResponse(
            'Comments fetched successfully',
            $comments
        );
    }

   
    public function count($link_id)
    {
        $count = Comment::where('link_id', $link_id)->count();

        return $this->successResponse(
            'Comment count fetched successfully',
            [
                'count' => $count,
            ]
        );
    }


    public function destroy($id)
    {
        Comment::where('id', $id)->delete();

        return $this->successResponse(
            'Comment deleted successfully',
            null
        );
    }
}