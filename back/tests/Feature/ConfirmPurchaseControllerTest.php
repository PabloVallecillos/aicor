<?php

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\Contracts\CartSessionServiceInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->actingAs($this->user, 'api');
});

test('successful purchase returns created order', function () {
    // Prepare
    $cart = Cart::factory()->create(['user_id' => $this->user->id]);
    $product1 = Product::factory()->create(['price' => 10.00, 'stock' => 3]);
    $product2 = Product::factory()->create(['price' => 25.50, 'stock' => 2]);
    CartItem::factory()->create([
        'cart_id' => $cart->id,
        'product_id' => $product1->id,
        'quantity' => 2,
        'price' => $product1->price,
    ]);
    CartItem::factory()->create([
        'cart_id' => $cart->id,
        'product_id' => $product2->id,
        'quantity' => 1,
        'price' => $product2->price,
    ]);

    $this->cartSessionService = Mockery::mock(CartSessionServiceInterface::class);
    $this->app->instance(CartSessionServiceInterface::class, $this->cartSessionService);

    $this->cartSessionService
        ->shouldReceive('ensureSessionExists')
        ->once();

    $this->cartSessionService
        ->shouldReceive('getSessionToken')
        ->once()
        ->andReturn('test-session-token');

    $expectedOrder = Order::make([
        'user_id' => $this->user->id,
        'guest_id' => null,
        'total_amount' => 45.50,
        'status' => Order::STATUS_PENDING,
    ]);
    $expectedOrder->id = 1;

    // Act
    $response = $this->postJson(route('api.purchase.confirm'));

    // Assert
    $response->assertStatus(Response::HTTP_CREATED)
        ->assertJson([
            'user_id' => $expectedOrder->user_id,
            'guest_id' => $expectedOrder->guest_id,
            'total_amount' => (string) $expectedOrder->total_amount,
            'status' => $expectedOrder->status,
        ]);
});
