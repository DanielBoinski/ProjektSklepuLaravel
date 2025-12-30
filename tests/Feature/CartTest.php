<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_cart(): void
    {
        $response = $this->get('/koszyk');
        $response->assertRedirect(route('login'));
    }

    public function test_client_can_access_cart(): void
    {
        $client = User::factory()->create(['role' => 'client']);

        $response = $this->actingAs($client)->get('/koszyk');
        $response->assertStatus(200);
        $response->assertSee('Twój koszyk');
    }

    public function test_client_can_add_product_to_cart(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $product = Product::create([
            'name' => 'Test Product',
            'price' => 99.99,
            'stock' => 10,
        ]);

        $response = $this->actingAs($client)->post('/koszyk/dodaj/' . $product->id);

        $response->assertRedirect();
        $this->assertEquals(1, session('cart')[$product->id]['quantity']);
    }

    public function test_adding_same_product_increases_quantity(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $product = Product::create([
            'name' => 'Test Product',
            'price' => 99.99,
            'stock' => 10,
        ]);

        $this->actingAs($client)->post('/koszyk/dodaj/' . $product->id);
        $this->actingAs($client)->post('/koszyk/dodaj/' . $product->id);

        $this->assertEquals(2, session('cart')[$product->id]['quantity']);
    }

    public function test_client_can_remove_product_from_cart(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $product = Product::create([
            'name' => 'Test Product',
            'price' => 99.99,
            'stock' => 10,
        ]);

        $this->actingAs($client)->post('/koszyk/dodaj/' . $product->id);
        $response = $this->actingAs($client)->delete('/koszyk/usun/' . $product->id);

        $response->assertRedirect(route('cart.index'));
        $this->assertEmpty(session('cart'));
    }

    public function test_checkout_creates_order(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $product = Product::create([
            'name' => 'Test Product',
            'price' => 99.99,
            'stock' => 10,
        ]);

        $this->actingAs($client)->post('/koszyk/dodaj/' . $product->id);
        $response = $this->actingAs($client)->post('/koszyk/zamow');

        $response->assertRedirect(route('client.dashboard'));
        $this->assertDatabaseHas('orders', [
            'user_id' => $client->id,
            'total_price' => 99.99,
        ]);
        $this->assertDatabaseHas('order_items', [
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => 99.99,
        ]);
    }

    public function test_checkout_decrements_stock(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $product = Product::create([
            'name' => 'Test Product',
            'price' => 99.99,
            'stock' => 10,
        ]);

        $this->actingAs($client)->post('/koszyk/dodaj/' . $product->id);
        $this->actingAs($client)->post('/koszyk/zamow');

        $product->refresh();
        $this->assertEquals(9, $product->stock);
    }

    public function test_checkout_fails_when_stock_insufficient(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $product = Product::create([
            'name' => 'Test Product',
            'price' => 99.99,
            'stock' => 1,
        ]);

        // Dodaj 2 produkty do koszyka
        $this->actingAs($client)->post('/koszyk/dodaj/' . $product->id);
        $this->actingAs($client)->post('/koszyk/dodaj/' . $product->id);

        $response = $this->actingAs($client)->post('/koszyk/zamow');

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('error');
    }

    public function test_empty_cart_checkout_fails(): void
    {
        $client = User::factory()->create(['role' => 'client']);

        $response = $this->actingAs($client)->post('/koszyk/zamow');

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('error', 'Koszyk jest pusty.');
    }
}
