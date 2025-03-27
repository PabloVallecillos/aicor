<?php

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;

uses(RefreshDatabase::class);

beforeEach(function () {
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
                '*' => [
                    'product',
                    'quantity',
                ],
            ],
            'total',
            'total_items',
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

it('can add multiple items to cart successfully', function () {
    $products = Product::factory()->count(3)->create();

    $requestData = [
        'items' => [
            [
                'product_id' => $products[0]->id,
                'quantity' => 2,
            ],
            [
                'product_id' => $products[1]->id,
                'quantity' => 3,
            ],
            [
                'product_id' => $products[2]->id,
                'quantity' => 1,
            ],
        ],
    ];

    $response = $this->postJson(route('api.cart.add.multiple'), $requestData);

    $response
        ->assertStatus(200)
        ->assertJson(function (AssertableJson $json) use ($products) {
            $json->has('items')
                ->whereType('items', 'array')
                ->has('items.0', function (AssertableJson $item) use ($products) {
                    $item->has('product')
                        ->where('product.id', $products[0]->id);
                    $item->where('quantity', 2);
                })
                ->has('items.1', function (AssertableJson $item) use ($products) {
                    $item->has('product')
                        ->where('product.id', $products[1]->id);
                    $item->where('quantity', 3);
                })
                ->has('items.2', function (AssertableJson $item) use ($products) {
                    $item->has('product')
                        ->where('product.id', $products[2]->id);
                    $item->where('quantity', 1);
                });

            $json->hasAll(['id', 'total', 'total_items']);
        });
});

it('throws an error when a product does not exist', function () {
    $id = 11199999;
    $requestData = [
        'items' => [
            [
                'product_id' => $id,
                'quantity' => 2,
            ],
        ],
    ];

    $response = $this->postJson(route('api.cart.add.multiple'), $requestData);

    $response
        ->assertStatus(404)
        ->assertJson([
            'message' => __('Product with ID :id not found', ['id' => $id]),
        ]);
});

it('validates request data', function () {
    $requestData = [
        'items' => [
            [
                'product_id' => null,
                'quantity' => -1,
            ],
        ],
    ];

    $response = $this->postJson(route('api.cart.add.multiple'), $requestData);

    $response
        ->assertStatus(422)
        ->assertJsonValidationErrors([
            'items.0.product_id',
            'items.0.quantity',
        ]);
});
