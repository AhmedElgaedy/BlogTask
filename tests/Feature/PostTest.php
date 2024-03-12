<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test creating a new post.
     *
     * @return void
     */
    public function testCreatePost()
    {
        $postData = [
            'title' => 'hamadaaaa',
            'content' => 'test hamaadaa',
        ];

        $response = $this->postJson('/api/posts', $postData);

        $response->assertStatus(201);
        $response->assertJson([
            'message' => 'Post created successfully.',
        ]);
        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post',
            'content' => 'This is a test post.',
        ]);
    }

    /**
     * Test retrieving a single post.
     *
     * @return void
     */
    public function testGetPost()
    {
        $post = factory(Post::class)->create();

        $response = $this->getJson('/api/posts/' . $post->id);

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $post->id,
            'title' => $post->title,
            'content' => $post->content,
        ]);
    }

    /**
     * Test updating an existing post.
     *
     * @return void
     */
    public function testUpdatePost()
    {
        $post = factory(Post::class)->create();

        $updatedData = [
            'title' => 'Updated Title',
            'content' => 'Updated content.',
        ];

        $response = $this->putJson('/api/posts/' . $post->id, $updatedData);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Post updated successfully.',
        ]);
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Updated Title',
            'content' => 'Updated content.',
        ]);
    }

    /**
     * Test deleting a post.
     *
     * @return void
     */
    public function testDeletePost()
    {
        $post = factory(Post::class)->create();

        $response = $this->deleteJson('/api/posts/' . $post->id);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Post deleted successfully.',
        ]);
        $this->assertDatabaseMissing('posts', [
            'id' => $post->id,
        ]);
    }
}