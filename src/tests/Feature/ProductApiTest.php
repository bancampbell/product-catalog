<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_can_get_products()
    {
        Category::factory()->create();
        Product::factory()->count(3)->create();

        $response = $this->getJson('/api/products');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(['data', 'meta']);
    }

    public function test_user_can_login_and_get_token()
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonPath('success', true);
        $response->assertJsonStructure(['data' => ['access_token']]);
    }

    public function test_unauthenticated_user_cannot_create_product()
    {
        $response = $this->postJson('/api/products', [
            'name' => 'Test Product',
            'price' => 999,
            'category_id' => 1,
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_authenticated_user_can_create_product()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        $category = Category::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/products', [
            'name' => 'Test Product',
            'price' => 999,
            'category_id' => $category->id,
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseHas('products', ['name' => 'Test Product']);
    }

    public function test_product_validation_fails()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/products', [
            'name' => '',
            'price' => -10,
            'category_id' => 999,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['name', 'price', 'category_id']);
    }
}
