<?php

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;

uses(RefreshDatabase::class);

beforeEach(function () {
    Config::set('jwt.secret', 'test_secret_key_that_is_long_enough');

    $this->user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password123'),
    ]);

    $this->product = Product::factory()->create([
        'name' => 'Test Product',
        'price' => 19.99,
    ]);

    $this->actingAs($this->user, 'api');
});

test('guest can view empty cart', function () {
    $response = $this->getJson(route('api.cart.get'));

    $response->assertStatus(200)
        ->assertJsonStructure([
            'id',
            'items',
            'total',
            'total_items',
        ])
        ->assertJson([
            'items' => [],
            'total' => 0,
            'total_items' => 0,
        ]);
});

test('can add item to cart', function () {
    $response = $this->postJson(route('api.cart.add', $this->product));

    $response->assertStatus(200)
        ->assertJsonStructure([
            'id',
            'items' => [
                '*' => ['id', 'name', 'quantity', 'price', 'total'],
            ],
            'total',
            'total_items',
        ])
        ->assertJsonFragment([
            'id' => $this->product->id,
            'name' => $this->product->name,
            'quantity' => 1,
            'price' => '19.99',
        ]);
});

test('can update item quantity', function () {
    $this->postJson(route('api.cart.add', $this->product));

    $response = $this->putJson(route('api.cart.update', [
        'product' => $this->product,
        'quantity' => 3,
    ]));

    $response->assertStatus(200)
        ->assertJsonFragment([
            'id' => $this->product->id,
            'quantity' => 3,
            'total' => 3 * 19.99,
        ]);
});

test('can remove item from cart', function () {
    $this->postJson(route('api.cart.add', $this->product));

    $response = $this->deleteJson(route('api.cart.remove', $this->product));

    $response->assertStatus(200)
        ->assertJsonFragment([
            'total' => 0,
            'total_items' => 0,
            'items' => [],
        ]);
});

test('can clear entire cart', function () {
    $anotherProduct = Product::factory()->create([
        'name' => 'Another Product',
        'price' => 29.99,
    ]);

    $this->postJson(route('api.cart.add', $this->product));
    $this->postJson(route('api.cart.add', $anotherProduct));

    $response = $this->deleteJson(route('api.cart.clear'));

    $response->assertStatus(200)
        ->assertJsonFragment([
            'total' => 0,
            'total_items' => 0,
            'items' => [],
        ]);
});

test('can view cart contents', function () {
    $this->postJson(route('api.cart.add', $this->product));

    $response = $this->getJson(route('api.cart.get'));

    $response->assertStatus(200)
        ->assertJsonFragment([
            'id' => $this->product->id,
            'quantity' => 1,
        ]);
});

test('cart persists across multiple requests', function () {
    $this->postJson(route('api.cart.add', $this->product));

    $firstResponse = $this->getJson(route('api.cart.get'));

    $firstResponse->assertStatus(200)
        ->assertJsonFragment([
            'total_items' => 1,
            'total' => 19.99,
        ]);

    $anotherProduct = Product::factory()->create([
        'name' => 'Another Product',
        'price' => 29.99,
    ]);
    $this->postJson(route('api.cart.add', $anotherProduct));

    $secondResponse = $this->getJson(route('api.cart.get'));

    $secondResponse->assertStatus(200)
        ->assertJsonFragment([
            'total_items' => 2,
            'total' => 19.99 + 29.99,
        ]);
});

test('adding same product increases quantity', function () {
    $this->postJson(route('api.cart.add', $this->product));

    $response = $this->postJson(route('api.cart.add', $this->product));

    $response->assertStatus(200)
        ->assertJsonFragment([
            'id' => $this->product->id,
            'quantity' => 2,
            'total' => 2 * 19.99,
        ]);
});
