<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreatePostRequest;
use Illuminate\Mail\Mailables\Content;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input("per_page", 10);

            $posts = Auth::user()
                ->posts()
                ->orderby("created_at", "desc")
                ->paginate($perPage);

            return response()->json($posts, 200);
        } catch (\Throwable $e) {
            return response()->json(["message" => $e->getMessage()], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreatePostRequest $request)
    {
        try {
            $validaed = $request->validated();

            $post = new Post(["id" => Str::uuid(), ...$validaed]);

            $post["user_id"] = Auth::id();
            $post->save();

            return response()->json(null, 201);
        } catch (\Throwable $e) {
            return response()->json(["message" => $e->getMessage()], 404);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        try {
            if (Auth::user()->id !== $post->user_id) {
                throw new \Exception("You can't do this");
            }

            return response()->json($post, 200);
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage()], 403);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreatePostRequest $request, Post $post)
    {
        try {
            if (Auth::user()->id !== $post->user_id) {
                throw new \Exception("You can't do this");
            }

            $validated = $request->validated();

            $post->update($validated);

            return response()->json(null, 201);
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage()], 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        try {
            if (Auth::user()->id !== $post->user_id) {
                throw new \Exception("You can't do this");
            }

            $post->delete();

            return response()->json($post, 204);
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage()], 403);
        }
    }
}
