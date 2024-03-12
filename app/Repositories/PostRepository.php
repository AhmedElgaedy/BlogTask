<?php

namespace App\Repositories;

use App\Models\Post;
use illuminate\http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;

class PostRepository
{
    public function all( $request)
    {

        return Cache::remember('posts', 60, function () use($request) {
            return Post::where(function($query) use ($request){
                if($request->user_id){
                $query->where('user_id', $request->user_id );
                }
                if ($request->title) {
                    $query->where('title', 'like', '%' . $request->title . '%');
                }
                if($request->content) {
                    $query->where('content', 'like', '%' . $request->content . '%');
                }
            })->paginate(10);
        });


      
    }

    public function create(array $data)
    {
        
        $post = Post::create($data);
        Cache::forget('posts');


        return $post;
    }
    
    public function find($id)
    {
        return Post::find($id);
    }


    public function update(Post $post, array $data)
    {
   

        if (Gate::denies('update-post', $post->id)) {
            abort(403, 'Unauthorized action.');
        }
        $post= $post->update($data);
        Cache::forget('posts');
        return $post;
    }

    public function delete($id)
    {
        if(Gate::denies('delete-post', $id)) {
            abort(403, 'Unauthorized action.');
        }
        $post= Post::find($id)->delete();
        Cache::forget('posts');
        return $post;
    }

}