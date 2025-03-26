<?php

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use App\Repositories\CartRepository;

uses()->group('cart-repository');

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->product = Product::factory()->create([
        'price' => 19.99,
    ]);
    $this->cartRepository = new CartRepository;
});

test('can find cart by user id', function () {
    $cart = Cart::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $foundCart = $this->cartRepository->findByUserOrSession($this->user->id, null);

    expect($foundCart)->not()->toBeNull()
        ->and($foundCart->id)->toBe($cart->id)
        ->and($foundCart->user_id)->toBe($this->user->id);
});

test('can find cart by guest session token', function () {
    $sessionToken = 'test-session-token';
    $cart = Cart::factory()->create([
        'guest_id' => $sessionToken,
    ]);

    $foundCart = $this->cartRepository->findByUserOrSession(null, $sessionToken);

    expect($foundCart)->not()->toBeNull()
        ->and($foundCart->id)->toBe($cart->id)
        ->and($foundCart->guest_id)->toBe($sessionToken);
});

test('can create a new cart', function () {
    $cartData = [
        'user_id' => $this->user->id,
        'status' => 'active',
    ];

    $cart = $this->cartRepository->create($cartData);

    expect($cart)->toBeInstanceOf(Cart::class)
        ->and($cart->user_id)->toBe($this->user->id);
});

test('can add item to cart', function () {
    $cart = Cart::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $updatedCart = $this->cartRepository->addItem($cart, $this->product, 2);

    expect($updatedCart->cartItems)->toHaveCount(1)
        ->and($updatedCart->cartItems->first()->product_id)->toBe($this->product->id)
        ->and($updatedCart->cartItems->first()->quantity)->toBe(2)
        ->and((float) $updatedCart->cartItems->first()->price)->toBe(19.99);
});

test('adding same item increases quantity', function () {
    $cart = Cart::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->cartRepository->addItem($cart, $this->product, 2);
    $updatedCart = $this->cartRepository->addItem($cart, $this->product, 3);

    expect($updatedCart->cartItems)->toHaveCount(1)
        ->and($updatedCart->cartItems->first()->quantity)->toBe(5);
});

test('can remove item from cart', function () {
    $cart = Cart::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->cartRepository->addItem($cart, $this->product, 2);
    $this->cartRepository->removeItem($cart, $this->product);

    $cart->refresh();
    expect($cart->cartItems)->toHaveCount(0);
});

test('can update item quantity', function () {
    $cart = Cart::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->cartRepository->addItem($cart, $this->product, 2);
    $updatedCart = $this->cartRepository->updateItemQuantity($cart, $this->product, 5);

    expect($updatedCart->cartItems)->toHaveCount(1)
        ->and($updatedCart->cartItems->first()->quantity)->toBe(5);
});

test('updating item quantity to zero removes the item', function () {
    $cart = Cart::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->cartRepository->addItem($cart, $this->product, 2);
    $updatedCart = $this->cartRepository->updateItemQuantity($cart, $this->product, 0);

    expect($updatedCart->cartItems)->toHaveCount(0);
});

test('can clear cart', function () {
    $cart = Cart::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->cartRepository->addItem($cart, $this->product, 2);
    $this->cartRepository->clearCart($cart);

    $cart->refresh();
    expect($cart->cartItems)->toHaveCount(0);
});

test('can add multiple different items to an empty cart', function () {
    $products = Product::factory()->count(3)->create([
        'price' => 19.99,
    ]);

    $cart = Cart::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $itemsToAdd = $products->map(function ($product) {
        return [
            'product' => $product,
            'quantity' => 2,
        ];
    })->toArray();

    $updatedCart = $this->cartRepository->addMultipleItems($cart, $itemsToAdd);

    expect($updatedCart->cartItems)->toHaveCount(3)
        ->and($updatedCart->cartItems->pluck('quantity')->toArray())->toBe([2, 2, 2])
        ->and($updatedCart->cartItems->pluck('product_id')->toArray())->toBe(
            $products->pluck('id')->toArray()
        );
});

test('can add multiple items with some already existing in cart', function () {
    $products = Product::factory()->count(3)->create([
        'price' => 19.99,
    ]);

    $cart = Cart::factory()->create([
        'user_id' => $this->user->id,
    ]);
    $this->cartRepository->addItem($cart, $products[0], 3);

    $itemsToAdd = [
        [
            'product' => $products[0],
            'quantity' => 2,
        ],
        [
            'product' => $products[1],
            'quantity' => 4,
        ],
        [
            'product' => $products[2],
            'quantity' => 1,
        ],
    ];

    $updatedCart = $this->cartRepository->addMultipleItems($cart, $itemsToAdd);

    expect($updatedCart->cartItems)->toHaveCount(3)
        ->and($updatedCart->cartItems->firstWhere('product_id', $products[0]->id)->quantity)->toBe(5)
        ->and($updatedCart->cartItems->firstWhere('product_id', $products[1]->id)->quantity)->toBe(4)
        ->and($updatedCart->cartItems->firstWhere('product_id', $products[2]->id)->quantity)->toBe(1);
});

test('handles adding multiple items with zero quantity', function () {
    $products = Product::factory()->count(3)->create([
        'price' => 19.99,
    ]);

    $cart = Cart::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $itemsToAdd = [
        [
            'product' => $products[0],
            'quantity' => 2,
        ],
        [
            'product' => $products[1],
            'quantity' => 0,
        ],
        [
            'product' => $products[2],
            'quantity' => 1,
        ],
    ];

    $updatedCart = $this->cartRepository->addMultipleItems($cart, $itemsToAdd);

    expect($updatedCart->cartItems)->toHaveCount(3)
        ->and($updatedCart->cartItems->pluck('product_id')->toArray())->toContain(
            $products[0]->id,
            $products[2]->id
        )
        ->and($updatedCart->cartItems->firstWhere('product_id', $products[0]->id)->quantity)->toBe(2)
        ->and($updatedCart->cartItems->firstWhere('product_id', $products[2]->id)->quantity)->toBe(1);
});

test('performance of adding multiple items', function () {
    $products = Product::factory()->count(100)->create([
        'price' => 19.99,
    ]);

    $cart = Cart::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $itemsToAdd = $products->map(function ($product) {
        return [
            'product' => $product,
            'quantity' => rand(1, 10),
        ];
    })->toArray();

    $startTime = microtime(true);

    $updatedCart = $this->cartRepository->addMultipleItems($cart, $itemsToAdd);

    $executionTime = microtime(true) - $startTime;

    expect($executionTime)->toBeLessThan(2.0)
        ->and($updatedCart->cartItems)->toHaveCount(100)
        ->and($updatedCart->cartItems->sum('quantity'))->toBe(
            collect($itemsToAdd)->sum('quantity')
        );
});
