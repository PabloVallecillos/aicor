<?php

use App\Models\Product;
use App\Services\CartService;
use App\Services\Contracts\AuthServiceInterface;
use App\Services\Contracts\CartRepositoryInterface;
use App\Services\Contracts\SessionServiceInterface;

beforeEach(function () {
    $this->cartRepository = Mockery::mock(CartRepositoryInterface::class);
    $this->authService = Mockery::mock(AuthServiceInterface::class);
    $this->sessionService = Mockery::mock(SessionServiceInterface::class);
});

it('can add an item to the cart', function () {
    // Arrange
    $product = Mockery::mock(Product::class);
    $product->shouldReceive('getAttribute')->with('id')->andReturn(1);
    $product->shouldReceive('getAttribute')->with('name')->andReturn('Test Product');
    $product->shouldReceive('getAttribute')->with('price')->andReturn(10.00);

    $cart = new stdClass;
    $cart->id = 1;
    $cart->cartItems = collect([
        (object) [
            'product' => $product,
            'quantity' => 1,
            'price' => 10.00,
        ],
    ]);

    $this->authService->shouldReceive('getCurrentUser')->once()->andReturn(null);
    $this->sessionService->shouldReceive('getSessionToken')->once()->andReturn('test-session');

    $this->cartRepository
        ->shouldReceive('findByUserOrSession')
        ->once()
        ->with(null, 'test-session')
        ->andReturn($cart);

    $this->cartRepository
        ->shouldReceive('addItem')
        ->once()
        ->with($cart, $product, 1)
        ->andReturn($cart);

    // Act
    $cartService = new CartService(
        $this->cartRepository,
        $this->authService,
        $this->sessionService
    );

    $result = $cartService->addItem($product);

    // Assert
    expect($result)->toHaveKeys(['id', 'items', 'total', 'total_items']);
    expect($result['total'])->toBe(10.00);
    expect($result['total_items'])->toBe(1);
});

it('can remove an item from the cart', function () {
    // Arrange
    $product = Mockery::mock(Product::class);
    $product->shouldReceive('getAttribute')->with('id')->andReturn(1);

    $cart = new stdClass;
    $cart->id = 1;
    $cart->cartItems = collect();

    $this->authService->shouldReceive('getCurrentUser')->once()->andReturn(null);
    $this->sessionService->shouldReceive('getSessionToken')->once()->andReturn('test-session');

    $this->cartRepository
        ->shouldReceive('findByUserOrSession')
        ->once()
        ->with(null, 'test-session')
        ->andReturn($cart);

    $this->cartRepository
        ->shouldReceive('removeItem')
        ->once()
        ->with($cart, $product);

    // Act
    $cartService = new CartService(
        $this->cartRepository,
        $this->authService,
        $this->sessionService
    );

    $result = $cartService->removeItem($product);

    // Assert
    expect($result)->toHaveKeys(['id', 'items', 'total', 'total_items']);
    expect($result['total'])->toBe(0);
    expect($result['total_items'])->toBe(0);
});

it('can update item quantity in the cart', function () {
    // Arrange
    $product = Mockery::mock(Product::class);
    $product->shouldReceive('getAttribute')->with('id')->andReturn(1);
    $product->shouldReceive('getAttribute')->with('name')->andReturn('Test Product');
    $product->shouldReceive('getAttribute')->with('price')->andReturn(10.00);

    $cart = new stdClass;
    $cart->id = 1;
    $cart->cartItems = collect([
        (object) [
            'product' => $product,
            'quantity' => 3,
            'price' => 10.00,
        ],
    ]);

    $this->authService->shouldReceive('getCurrentUser')->once()->andReturn(null);
    $this->sessionService->shouldReceive('getSessionToken')->once()->andReturn('test-session');

    $this->cartRepository
        ->shouldReceive('findByUserOrSession')
        ->once()
        ->with(null, 'test-session')
        ->andReturn($cart);

    $this->cartRepository
        ->shouldReceive('updateItemQuantity')
        ->once()
        ->with($cart, $product, 3)
        ->andReturn($cart);

    // Act
    $cartService = new CartService(
        $this->cartRepository,
        $this->authService,
        $this->sessionService
    );

    $result = $cartService->updateItemQuantity($product, 3);

    // Assert
    expect($result)->toHaveKeys(['id', 'items', 'total', 'total_items']);
    expect($result['total'])->toBe(30.00);
    expect($result['total_items'])->toBe(3);
});

it('can clear the cart', function () {
    // Arrange
    $cart = new stdClass;
    $cart->id = 1;
    $cart->cartItems = collect();

    $this->authService->shouldReceive('getCurrentUser')->once()->andReturn(null);
    $this->sessionService->shouldReceive('getSessionToken')->once()->andReturn('test-session');

    $this->cartRepository
        ->shouldReceive('findByUserOrSession')
        ->once()
        ->with(null, 'test-session')
        ->andReturn($cart);

    $this->cartRepository
        ->shouldReceive('clearCart')
        ->once()
        ->with($cart);

    // Act
    $cartService = new CartService(
        $this->cartRepository,
        $this->authService,
        $this->sessionService
    );

    $result = $cartService->clear();

    // Assert
    expect($result)->toHaveKeys(['id', 'items', 'total', 'total_items']);
    expect($result['total'])->toBe(0);
    expect($result['total_items'])->toBe(0);
});

afterEach(function () {
    Mockery::close();
});
