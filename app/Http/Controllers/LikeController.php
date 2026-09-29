<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Notifications\PostLiked;

class LikeController extends Controller
{
    public function toggle(Request $request, Post $post)
    {
        $user = $request->user();
        $like = $user->likes()->where('post_id', $post->id)->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            $user->likes()->create(['post_id' => $post->id]);
            if ($post->user_id !== $user->id) {
                $post->user->notify(new PostLiked($user, $post));
            }
            $liked = true;
        }
        Cache::tags(['posts'])->flush();

        return response()->json([
            'liked' => $liked,
            'likes_count' => $post->likes()->count(),
        ]);
    }
}
