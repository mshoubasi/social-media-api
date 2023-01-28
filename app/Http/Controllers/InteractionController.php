<?php

namespace App\Http\Controllers;

use App\Enum\InteractionEnum;
use App\Models\Comment;
use App\Models\Interaction;
use App\Models\Post;
use Illuminate\Http\Request;

class InteractionController extends Controller
{
    public function post($id, $interact)
    {
        $post = Post::find($id);

        if(!$post)
        {
            return response([
                'message' => 'Post not found'
            ], 403);
        }

        $interaction = $post->interaction()->where('user_id', auth()->user()->id)->first();

        if(!$interaction)
        {
            Interaction::create([
                'post_id' => $id,
                'user_id' => auth()->user()->id,
                'interact' => $interact ,
            ]);

            return response([
                'message' => $interact
            ], 200);
        }

        $interaction->delete();

        return response([
            'message' => 'Deleted'
        ], 200);

    }

    public function comment($id, $interact)
    {
        $comment = Comment::find($id);

        if(!$comment)
        {
            return response([
                'message' => 'Comment not found'
            ], 403);
        }

        $interaction = $comment->interaction()->where('user_id', auth()->user()->id)->first();

        if(!$interaction)
        {
            Interaction::create([
                'comment_id' => $id,
                'user_id' => auth()->user()->id,
                'interact' => $interact ,
            ]);

            return response([
                'message' => $interact
            ], 200);
        }

       $interaction->delete();

            return response([
                'message' => 'Deleted'
            ], 200);
    }
}
