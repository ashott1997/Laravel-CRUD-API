<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::query();

        if (request('limit')) {
            $posts->limit(request('limit'));
        }

        if (request('search')) {
            $posts->where('title', 'like', '%' . request('search') . '%');
        }

        if (request('order') && request('order_by')) {
            $posts->orderBy(request('order'), request('order_by'));
        }

        if(request('published')) {
            $posts->where('published', request('published'));
        }

        if(request('created_at')) {
            $posts->whereDate('created_at', request('created_at'));
        }

        if(request('updated_at')) {
            $posts->whereDate('updated_at', request('updated_at'));
        }

        if(request('created_at_from') && request('created_at_to')) {
            $posts->whereBetween('created_at', [request('created_at_from'), request('created_at_to')]);
        }

        if(request('updated_at_from') && request('updated_at_to')) {
            $posts->whereBetween('updated_at', [request('updated_at_from'), request('updated_at_to')]);
        }

        $posts = $posts->get();

        $response = [
            'posts' => $posts,
            'message' => 'Retrieved successfully'
        ];

        return response($response, 200);
    }

    public function store(Request $request): \Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $postFields = $request->validate([
            'title' => 'required|string',
            'body' => 'required|string',
            'published' => 'boolean',
        ]);

        $post = Post::create($postFields);

        $response = [
            'post' => $post,
            'message' => 'Post created successfully'
        ];

        return response($response, 201);
    }

    public function show(Post $post): \Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $response = [
            'post' => $post,
            'message' => 'Retrieved successfully'
        ];

        return response($response, 200);
    }

    public function update(Request $request, Post $post): \Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $postFields = $request->validate([
            'title' => 'string',
            'body' => 'string',
        ]);

        $post->update($postFields);

        $response = [
            'post' => $post,
            'message' => 'Post updated successfully'
        ];

        return response($response, 200);
    }

    public function destroy(Post $post): \Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $post->delete();

        $response = [
            'message' => 'Post deleted successfully'
        ];

        return response($response, 200);
    }


}
