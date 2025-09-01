<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_user_can_list_their_categories()
    {
        Category::factory()->count(3)->create(['user_id' => $this->user->id]);
        Category::factory()->count(2)->create(); // Other user's categories

        $response = $this->actingAs($this->user)->getJson('/api/categories');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_user_can_create_category()
    {
        $categoryData = [
            'name' => 'Test Category',
            'type' => 'income',
        ];

        $response = $this->actingAs($this->user)->postJson('/api/categories', $categoryData);

        $response->assertStatus(201)
            ->assertJsonFragment($categoryData);

        $this->assertDatabaseHas('categories', array_merge($categoryData, ['user_id' => $this->user->id]));
    }

    public function test_user_can_view_their_category()
    {
        $category = Category::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->getJson("/api/categories/{$category->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => $category->name]);
    }

    public function test_user_cannot_view_other_users_category()
    {
        $otherUser = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($this->user)->getJson("/api/categories/{$category->id}");

        $response->assertStatus(403);
    }

    public function test_user_can_update_their_category()
    {
        $category = Category::factory()->create(['user_id' => $this->user->id]);

        $updateData = ['name' => 'Updated Category Name'];

        $response = $this->actingAs($this->user)->putJson("/api/categories/{$category->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonFragment($updateData);

        $this->assertDatabaseHas('categories', array_merge(['id' => $category->id], $updateData));
    }

    public function test_user_cannot_update_other_users_category()
    {
        $otherUser = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $otherUser->id]);

        $updateData = ['name' => 'Updated Category Name'];

        $response = $this->actingAs($this->user)->putJson("/api/categories/{$category->id}", $updateData);

        $response->assertStatus(403);
    }

    public function test_user_can_delete_their_category()
    {
        $category = Category::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->deleteJson("/api/categories/{$category->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_user_cannot_delete_other_users_category()
    {
        $otherUser = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($this->user)->deleteJson("/api/categories/{$category->id}");

        $response->assertStatus(403);
    }
}
