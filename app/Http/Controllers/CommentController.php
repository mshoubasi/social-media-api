<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;

class CommentController extends Controller
{
    // get all comments of a post
    public function index($id)
    {
        $post = Post::find($id);

        if(!$post)
        {
            return response([
                'message' => 'Post not found.'
            ], 403);
        }

        return response([
            'comments' => $post->comments()->with('user:id,name')->get()
        ], 200);
    }

    // create a comment
    public function store(Request $request, $id)
    {
        $post = Post::find($id);

        if(!$post)
        {
            return response([
                'message' => 'Post not found.'
            ], 403);
        }

        //validate fields
        $request->validate([
            'comment' => 'required|string',
            'media'  => 'nullable'
        ]);

        $comment = Comment::create([
            'comment' => $request->comment,
            'post_id' => $id,
            'user_id' => auth()->user()->id
        ]);

        if($request->hasFile('media'))
        {
            $comment->addMultipleMediaFromRequest(['media'])
                ->each(function($fileAdder)
                 {
                    $fileAdder->toMediaCollection('CommentMedia');
                });

        }

        return response([
            'message' => 'Comment created.'
        ], 200);
    }

    // update a comment
    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);

        if(!$comment)
        {
            return response([
                'message' => 'Comment not found.'
            ], 403);
        }

        if($comment->user_id != auth()->user()->id)
        {
            return response([
                'message' => 'Permission denied.'
            ], 403);
        }

        //validate fields
         $request->validate([
            'comment' => 'required|string'
        ]);

        $comment->update([
            'comment' => $request->comment
        ]);

        if ($request->hasFile('media')) {
            $comment->clearMediaCollection('CommentMedia');
            $comment->addMultipleMediaFromRequest(['media'])
               ->each(function ($fileAdder) {
                   $fileAdder->toMediaCollection('CommentMedia');
               });
       }

        return response([
            'message' => 'Comment updated.'
        ], 200);
    }

    // delete a comment
    public function destroy($id)
    {
        $comment = Comment::find($id);

        if(!$comment)
        {
            return response([
                'message' => 'Comment not found.'
            ], 403);
        }

        if($comment->user_id != auth()->user()->id)
        {
            return response([
                'message' => 'Permission denied.'
            ], 403);
        }

        $comment->interaction()->delete();
        $comment->delete();

        return response([
            'message' => 'Comment deleted.'
        ], 200);
    }
}

