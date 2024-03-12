<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\PostService;
use App\Http\Requests\PostRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;

class PostController extends Controller
{
    
    private $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }
 
    public function index(Request $request)
    {
        $posts= $this->postService->getAll($request);
        return  PostResource::collection($posts);
    }

    public function store(PostRequest $request)
    {
        $data = $request->validated();
        $post = $this->postService->create($data);
        return PostResource::make($post);
    }

    public function show($id)
    {
        $post = $this->postService->find($id);
        return PostResource::make($post);
    }


    public function update(PostRequest $request, Post $post)
    {
        $data = $request->validated();
        $this->postService->update($post, $data);
        return PostResource::make($post);
    }


    public function destroy($id)
    {
        $this->postService->delete($id);
        return response()->json(null, 204);
    }

}