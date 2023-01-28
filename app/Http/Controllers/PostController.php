<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        return response([

            'posts' => Post::orderby('created_at', 'desc')->with('user_id, name')->withcount('comments')->get()
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:50',
            'body'  => 'required|string|max:255',
            'media' => 'nullable'
        ]);

        $post = Post::create([
            'body'    => $request->body,
            'title'   => $request->title,
            'user_id' => auth()->user()->id
        ]);

        if($request->hasFile('media'))
        {
            $post->addMultipleMediaFromRequest(['media'])
                ->each(function($fileAdder)
                 {
                    $fileAdder->toMediaCollection('media');
                });

        }

        return response([
            'message' => 'Post Created.',
            'post' => $post,
        ]);
    }

    public function show($id)
    {
        return response([
            'post' => Post::where('id', $id)->withcount('comments')->first()
        ],200);
    }

    public function update(Request $request, $id)
    {
        $post = Post::find($id);

        if(!$post)
        {
            return response([
                'message' => 'Post not found'
            ],403);
        }

        if($post->user_id != auth()->user()->id)
        {
            return response([
                'message' => 'Denied'
            ],403);
        }

         $request->validate([
            'body' => 'required|string|max:255',
            'title' => 'required|string|max:50',
            ]);

        $post->update([
            'body' => $request->body,
            'title' => $request->title,
        ]);

        if ($request->hasFile('media')) {
            $post->clearMediaCollection('media');
            $post->addMultipleMediaFromRequest(['media'])
               ->each(function ($fileAdder) {
                   $fileAdder->toMediaCollection('media');
               });
       }

        return response([
            'message' => 'Post updated.',
            'post' => $post
        ], 200);
    }

    public function destroy($id)
    {
        $post = Post::find($id);

        if(!$post)
        {
            return response([
                'message' => 'Post not found'
            ],403);
        }

        if($post->user_id != auth()->user()->id)
        {
            return response([
                'message' => 'Denied'
            ],403);
        }


          $post->comments()->delete();
          $post->interaction()->delete();
          $post->delete();

        return response([
            'message' => 'post deleted'
        ],200);
    }

}
