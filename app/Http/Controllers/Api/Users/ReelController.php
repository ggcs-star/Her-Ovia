<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Models\Reel;
use App\Models\ReelLike;
use App\Models\ReelComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Helpers\S3Helper;
class ReelController extends Controller
{
    public function index(Request $request)
    {
        $ip = $request->ip();

        $reels = Reel::with('platformProduct.product')
            ->where('status',1)
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $data = $reels->getCollection()->map(function ($reel) use ($ip){

            $product = optional(optional($reel->platformProduct)->product);

            $isLiked = ReelLike::where('reel_id',$reel->id)
                ->where('ip_address',$ip)
                ->exists();

            return [

                'id' => $reel->id,
                'title' => $reel->title,
                'description' => $reel->description,

                'video' => S3Helper::url($reel->video),

                'views' => $reel->views_count,
                'likes' => $reel->likes_count,
                'shares' => $reel->shares_count,
                'comments' => $reel->comments_count,

                'is_liked' => $isLiked,

                'product' => [
                    'id' => $product->id ?? null,
                    'name' => $product->name ?? null,
                    'slug' => $product->slug ?? null,
                    'price' => optional($reel->platformProduct)->platform_price ?? null,
                    'image' => $product->image ? asset($product->image) : null,
                ],

                'created_at' => $reel->created_at
            ];
        });

        return $this->successResponse(
            'Reels fetched successfully',
            [
                'reels' => $data,
                'pagination' => [
                    'current_page' => $reels->currentPage(),
                    'last_page' => $reels->lastPage(),
                    'per_page' => $reels->perPage(),
                    'total' => $reels->total(),
                ],
            ]
        );
    }

    public function show($id, Request $request)
    {
        $ip = $request->ip();

        $reel = Reel::with('platformProduct.product')->findOrFail($id);

        $product = optional(optional($reel->platformProduct)->product);

        $isLiked = ReelLike::where('reel_id',$id)
            ->where('ip_address',$ip)
            ->exists();

        return $this->successResponse(
        'Reel fetched successfully',
        [
            'id' => $reel->id,
            'title' => $reel->title,
            'description' => $reel->description,
            'video' => S3Helper::url($reel->video),

            'views' => $reel->views_count,
            'likes' => $reel->likes_count,
            'shares' => $reel->shares_count,
            'comments' => $reel->comments_count,

            'is_liked' => $isLiked,

            'product' => [
                'id' => $product->id ?? null,
                'name' => $product->name ?? null,
                'slug' => $product->slug ?? null,
                'price' => optional($reel->platformProduct)->platform_price ?? null,
                'image' => $product->image ? asset($product->image) : null,
            ],

            'created_at' => $reel->created_at
        ]
    );
    }

    public function increaseViews($id, Request $request)
    {
        $ip = $request->ip();

        $reel = Reel::findOrFail($id);

        // prevent duplicate views (optional logic)
        $alreadyViewed = cache()->has("reel_view_{$id}_{$ip}");

        if(!$alreadyViewed){
            $reel->increment('views_count');
            cache()->put("reel_view_{$id}_{$ip}", true, 3600); // 1 hour
        }

        return $this->successResponse(
            'Reel views updated successfully',
            [
                'views' => $reel->views_count,
            ]
        );
    }

    public function like($id, Request $request)
    {
        $reel = Reel::findOrFail($id);

        $ip = $request->ip();

        $existing = ReelLike::where('reel_id',$id)
            ->where('ip_address',$ip)
            ->first();

        if($existing){

            $existing->delete();
            $reel->decrement('likes_count');

            return $this->successResponse(
                'Reel unliked successfully',
                [
                    'liked' => false,
                    'likes' => $reel->likes_count,
                ]
            );
        }

        ReelLike::create([
            'reel_id'=>$id,
            'ip_address'=>$ip
        ]);

        $reel->increment('likes_count');

        return $this->successResponse(
            'Reel liked successfully',
            [
                'liked' => true,
                'likes' => $reel->likes_count,
            ]
        );
    }

    public function share($id)
    {
        $reel = Reel::findOrFail($id);

        $reel->increment('shares_count');

        return $this->successResponse(
            'Reel shared successfully',
            [
                'shares' => $reel->shares_count,
            ]
        );
    }

public function comments($id)
{
    $comments = ReelComment::with('user')
        ->where('reel_id',$id)
        ->latest()
        ->take(20)
        ->get()
        ->map(function($c){

            $user = $c->user; // safe reference

            return [
                'id' => $c->id,
                'comment' => $c->comment,
                'time' => optional($c->created_at)->diffForHumans(),

                'user' => [
                    'id' => $user->id ?? null,
                    'name' => $user->name ?? 'User',

                    'avatar' => ($user && $user->avatar)
                        ? asset($user->avatar)
                        : 'https://ui-avatars.com/api/?name=' . urlencode($user->name ?? 'User')
                ]
            ];
        });

            return $this->successResponse(
            'Reel comments fetched successfully',
            $comments
        );
}
   public function addComment($id, Request $request)
{
    $request->validate([
        'comment'=>'required|string|max:500'
    ]);

    $user = auth('sanctum')->user(); // optional

    $comment = ReelComment::create([
        'reel_id' => $id,
        'user_id' => $user?->id, // ✅ nullable
        'comment' => $request->comment
    ]);

    Reel::where('id',$id)->increment('comments_count');

    return $this->successResponse(
        'Comment added successfully',
        null
    );
}
}