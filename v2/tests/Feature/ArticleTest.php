<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_published_articles()
    {
        $category = Category::factory()->create();
        Article::factory()->count(3)->create([
            'category_id' => $category->id,
            'is_published' => true,
        ]);
        
        // Unpublished article
        Article::factory()->create([
            'category_id' => $category->id,
            'is_published' => false,
        ]);

        $response = $this->getJson('/api/articles');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_guest_can_view_single_published_article()
    {
        $category = Category::factory()->create();
        $article = Article::factory()->create([
            'category_id' => $category->id,
            'is_published' => true,
            'view_count' => 0,
        ]);

        $response = $this->getJson("/api/articles/{$article->id}");

        $response->assertStatus(200)
            ->assertJson([
                'id' => $article->id,
                'title' => $article->title,
            ]);
            
        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'view_count' => 1,
        ]);
    }

    public function test_admin_can_manage_articles()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $token = $user->createToken('admin-test')->plainTextToken;
        $category = Category::factory()->create();

        $data = [
            'title' => 'New Product',
            'content' => 'Description',
            'category_id' => $category->id,
            'price' => 100.50,
            'is_published' => true,
        ];

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
             ->postJson('/api/admin/articles', $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['title' => 'New Product']);

        $this->assertDatabaseHas('articles', [
            'title' => 'New Product',
            'price' => 100.50,
        ]);
    }

    public function test_admin_can_update_article()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $token = $user->createToken('admin-test')->plainTextToken;
        $category = Category::factory()->create();
        $article = Article::factory()->create(['category_id' => $category->id]);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->putJson("/api/admin/articles/{$article->id}", [
                'title' => 'Updated Title'
            ]);

        $response->assertStatus(200)
             ->assertJsonFragment(['title' => 'Updated Title']);
             
        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'title' => 'Updated Title'
        ]);
    }

    public function test_admin_can_delete_article()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $token = $user->createToken('admin-test')->plainTextToken;
        $category = Category::factory()->create();
        $article = Article::factory()->create(['category_id' => $category->id]);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->deleteJson("/api/admin/articles/{$article->id}");

        $response->assertStatus(200);
        $this->assertSoftDeleted('articles', ['id' => $article->id]);
    }
}
