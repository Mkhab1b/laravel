<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Tests\TestCase;
use App\Models\Category;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test creating a category
     */
    public function test_create_category()
    {
        $response = $this->postJson('/api/categories', [
            'name' => 'Test Category',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('categories', ['name' => 'Test Category']);
    }

    /**
     * Test updating a category
     */
    public function test_update_category()
    {
        $category = Category::factory()->create();

        $response = $this->putJson('/api/categories/' . $category->id, [
            'name' => 'Updated Category Name',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('categories', ['name' => 'Updated Category Name']);
    }

    /**
     * Test deleting a category
     */
    public function test_delete_category()
    {
        $category = Category::factory()->create();

        $response = $this->deleteJson('/api/categories/' . $category->id);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    /**
     * Test getting a category
     */
    public function test_get_category()
    {
        $category = Category::factory()->create();

        $response = $this->get('/api/categories/' . $category->id);

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $category->id]);
    }

    /**
     * Test getting a paginated list of categories
     */
    public function test_get_paginated_categories()
    {
        Category::factory()->count(10)->create();

        $response = $this->get('/api/categories');

        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data');
    }
}
