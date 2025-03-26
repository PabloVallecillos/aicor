<?php

use App\Services\Contracts\CartSessionServiceInterface;
use Illuminate\Cookie\CookieJar;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

beforeEach(function () {
    $this->request = Mockery::mock(Request::class);
    $this->cookieJar = Mockery::mock(CookieJar::class);
});

it('ensures session exists', function () {
    // Arrange
    $mockSession = Mockery::mock(SessionInterface::class);

    $this->request
        ->shouldReceive('hasSession')
        ->once()
        ->andReturn(false);

    $this->request
        ->shouldReceive('getSession')
        ->once()
        ->andReturn($mockSession);

    // Create a mock implementation for testing
    $cartSessionService = new class($this->request, $this->cookieJar) implements CartSessionServiceInterface
    {
        public function __construct(
            private Request $request,
            private CookieJar $cookieJar
        ) {}

        public function ensureSessionExists(): void
        {
            if (! $this->request->hasSession()) {
                $this->request->getSession();
            }
        }

        public function getSessionToken(): ?string
        {
            return $this->request->getSession()->getId();
        }
    };

    // Act & Assert
    expect(fn () => $cartSessionService->ensureSessionExists())->not()->toThrow(Exception::class);
});

it('retrieves session token', function () {
    // Arrange
    $mockSession = Mockery::mock(SessionInterface::class);
    $mockSession
        ->shouldReceive('getId')
        ->once()
        ->andReturn('test-session-token');

    $this->request
        ->shouldReceive('getSession')
        ->once()
        ->andReturn($mockSession);

    // Create a mock implementation for testing
    $cartSessionService = new class($this->request, $this->cookieJar) implements CartSessionServiceInterface
    {
        public function __construct(
            private Request $request,
            private CookieJar $cookieJar
        ) {}

        public function ensureSessionExists(): void
        {
            if (! $this->request->hasSession()) {
                $this->request->getSession();
            }
        }

        public function getSessionToken(): ?string
        {
            return $this->request->getSession()->getId();
        }
    };

    // Act
    $sessionToken = $cartSessionService->getSessionToken();

    // Assert
    expect($sessionToken)->toBe('test-session-token');
});

afterEach(function () {
    Mockery::close();
});
