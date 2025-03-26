<?php

use App\Services\SessionService;
use Illuminate\Http\Request;

use function Pest\Laravel\mock;

it('returns the session token from the cookie', function () {
    $sessionKey = SessionService::SESSION_KEY;

    // Arrange
    $mockRequest = mock(Request::class);
    $mockRequest->shouldReceive('cookie')
        ->once()
        ->with($sessionKey)
        ->andReturn('test_session_token');

    $sessionService = new SessionService($mockRequest);

    // Act
    $token = $sessionService->getSessionToken();

    // Assert
    expect($token)->toBe('test_session_token');
});

it('returns null when the session token cookie is missing', function () {
    // Arrange
    $sessionKey = SessionService::SESSION_KEY;

    $mockRequest = mock(Request::class);
    $mockRequest->shouldReceive('cookie')
        ->once()
        ->with($sessionKey)
        ->andReturn(null);

    $sessionService = new SessionService($mockRequest);

    // Act
    $token = $sessionService->getSessionToken();

    // Assert
    expect($token)->toBeNull();
});

it('calls the cookie method with the correct session key', function () {
    // Arrange
    $sessionKey = SessionService::SESSION_KEY;

    $mockRequest = mock(Request::class);
    $mockRequest->shouldReceive('cookie')
        ->once()
        ->with($sessionKey)
        ->andReturn('some_token');

    $sessionService = new SessionService($mockRequest);

    // Act
    $sessionService->getSessionToken();

    // Assert
    // No assertion needed, if the method is called with incorrect key, the test fails.
});
