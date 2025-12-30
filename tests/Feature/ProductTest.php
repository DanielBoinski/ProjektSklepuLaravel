<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_shop_page_is_accessible(): void
    {
        $response = $this->get('/sklep');
        $response->assertStatus(200);
        $response->assertSee('Asortyment sklepu');
    }

    public function test_shop_displays_products(): void
    {
        Product::create([
            'name' => 'Test Product',
            'price' => 99.99,
            'stock' => 10,
        ]);

        $response = $this->get('/sklep');
        $response->assertSee('Test Product');
        $response->assertSee('99,99');
    }

    public function test_shop_search_works(): void
    {
        Product::create([
            'name' => 'Apple iPhone',
            'price' => 999.99,
            'stock' => 5,
        ]);
        Product::create([
            'name' => 'Samsung Galaxy',
            'price' => 899.99,
            'stock' => 5,
        ]);

        $response = $this->get('/sklep?q=iPhone');
        $response->assertSee('Apple iPhone');
        $response->assertDontSee('Samsung Galaxy');
    }

    public function test_admin_can_create_product(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post('/products', [
            'name' => 'New Product',
            'price' => 49.99,
            'stock' => 20,
            'description' => 'Product description',
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', [
            'name' => 'New Product',
            'price' => 49.99,
        ]);
    }

    public function test_admin_can_update_product(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $product = Product::create([
            'name' => 'Old Name',
            'price' => 99.99,
            'stock' => 10,
        ]);

        $response = $this->actingAs($admin)->put('/products/' . $product->id, [
            'name' => 'New Name',
            'price' => 149.99,
            'stock' => 15,
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'New Name',
            'price' => 149.99,
        ]);
    }

    public function test_admin_can_delete_product(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $product = Product::create([
            'name' => 'Product to Delete',
            'price' => 99.99,
            'stock' => 10,
        ]);

        $response = $this->actingAs($admin)->delete('/products/' . $product->id);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }

    public function test_moderator_can_create_product(): void
    {
        $moderator = User::factory()->create(['role' => 'moderator']);

        $response = $this->actingAs($moderator)->post('/products', [
            'name' => 'Moderator Product',
            'price' => 29.99,
            'stock' => 5,
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', [
            'name' => 'Moderator Product',
        ]);
    }

    public function test_product_validation_works(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post('/products', [
            'name' => '',
            'price' => -10,
            'stock' => -5,
        ]);

        $response->assertSessionHasErrors(['name', 'price', 'stock']);
    }

    public function test_product_image_upload(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post('/products', [
            'name' => 'Product with Image',
            'price' => 99.99,
            'stock' => 10,
            'image' => UploadedFile::fake()->image('product.jpg'),
        ]);

        $response->assertRedirect(route('products.index'));
        $product = Product::where('name', 'Product with Image')->first();
        $this->assertNotNull($product->image_path);
        Storage::disk('public')->assertExists($product->image_path);
    }
}
