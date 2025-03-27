<?php

use App\Models\Product;
use App\Repositories\Contracts\CartRepositoryInterface;
use App\Services\CartService;
use App\Services\Contracts\AuthServiceInterface;
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

it('can add multiple items to the cart', function () {
    // Arrange
    $product1 = Mockery::mock(Product::class);
    $product1->shouldReceive('getAttribute')->with('id')->andReturn(1);
    $product1->shouldReceive('getAttribute')->with('name')->andReturn('Product 1');
    $product1->shouldReceive('getAttribute')->with('price')->andReturn(10.00);

    $product2 = Mockery::mock(Product::class);
    $product2->shouldReceive('getAttribute')->with('id')->andReturn(2);
    $product2->shouldReceive('getAttribute')->with('name')->andReturn('Product 2');
    $product2->shouldReceive('getAttribute')->with('price')->andReturn(20.00);

    $cart = new stdClass;
    $cart->id = 1;
    $cart->cartItems = collect([
        (object) [
            'product' => $product1,
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
        ->shouldReceive('addMultipleItems')
        ->once()
        ->with($cart, [
            ['product' => $product1, 'quantity' => 2],
            ['product' => $product2, 'quantity' => 3],
        ])
        ->andReturnUsing(function ($cart, $products) {
            foreach ($products as $productData) {
                $product = $productData['product'];
                $quantity = $productData['quantity'];

                $existingItemIndex = $cart->cartItems->search(function ($item) use ($product) {
                    return $item->product->id === $product->id;
                });

                if ($existingItemIndex !== false) {
                    $cart->cartItems[$existingItemIndex]->quantity += $quantity;
                } else {
                    $newItem = (object) [
                        'product' => $product,
                        'quantity' => $quantity,
                        'price' => $product->price,
                    ];
                    $cart->cartItems->push($newItem);
                }
            }

            return $cart;
        });

    // Act
    $cartService = new CartService(
        $this->cartRepository,
        $this->authService,
        $this->sessionService
    );

    $products = [
        ['product' => $product1, 'quantity' => 2],
        ['product' => $product2, 'quantity' => 3],
    ];
    $result = $cartService->addMultipleItems($products);

    // Assert
    expect($result)->toHaveKeys(['id', 'items', 'total', 'total_items']);
    expect($result['total'])->toBe(90.00);  // (1 + 2) * 10 + 3 * 20
    expect($result['total_items'])->toBe(6);  // 1 + 2 + 3
});

it('performs efficiently when adding multiple items', function () {
    // Arrange
    $mockProducts = collect(range(1, 100))->map(function ($i) {
        $product = Mockery::mock(Product::class);
        $product->shouldReceive('getAttribute')->with('id')->andReturn($i);
        $product->shouldReceive('getAttribute')->with('price')->andReturn(10.00);
        $product->shouldReceive('getAttribute')->with('name')->andReturn("Product {$i}");

        return $product;
    });

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

    $cartItemsData = $mockProducts->map(function ($product) {
        return [
            'product' => $product,
            'quantity' => rand(1, 10),
        ];
    })->toArray();

    $this->cartRepository
        ->shouldReceive('addMultipleItems')
        ->once()
        ->with($cart, $cartItemsData)
        ->andReturnUsing(function ($cart, $products) {
            foreach ($products as $productData) {
                $product = $productData['product'];
                $quantity = $productData['quantity'];

                $newItem = (object) [
                    'product' => $product,
                    'quantity' => $quantity,
                    'price' => $product->getAttribute('price'),
                ];
                $cart->cartItems->push($newItem);
            }

            return $cart;
        });

    // Act
    $cartService = new CartService(
        $this->cartRepository,
        $this->authService,
        $this->sessionService
    );

    $startTime = microtime(true);

    $result = $cartService->addMultipleItems($cartItemsData);

    $executionTime = microtime(true) - $startTime;

    // Assert
    expect($executionTime)->toBeLessThan(1.0)
        ->and($result)->toHaveKeys(['id', 'items', 'total', 'total_items'])
        ->and($result['total_items'])->toBe(
            collect($cartItemsData)->sum('quantity')
        );
});

afterEach(function () {
    Mockery::close();
});
