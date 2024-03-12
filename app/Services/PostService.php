<?php 

namespace App\Services;

use App\Models\Post;
use App\Repositories\PostRepository;
use GuzzleHttp\Psr7\Request;

class PostService
{

    private $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function getAll($request)
    {
        return $this->postRepository->all($request);
    }

    public function create(array $data)
    {
        return $this->postRepository->create($data);
    }

    public function update(Post $post, array $data)
    {
        return $this->postRepository->update($post, $data);
    }

    public function find($id)
    {
        return $this->postRepository->find($id);
    }
    
    public function delete($id)
    {
        return $this->postRepository->delete($id);
    }
    
   

  
}