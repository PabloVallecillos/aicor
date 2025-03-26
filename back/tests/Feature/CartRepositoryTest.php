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
