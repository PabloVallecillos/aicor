<?php

use App\Services\AuthService;
use Illuminate\Support\Facades\Auth;

describe('AuthService', function () {
    it('returns the authenticated user', function () {
        // Arrange
        $mockUser = (object) ['id' => 1, 'name' => 'John Doe'];

        Auth::shouldReceive('user')
            ->once()
            ->andReturn($mockUser);

        $authService = new AuthService;

        // Act
        $user = $authService->getCurrentUser();

        // Assert
        expect($user)->toBe($mockUser);
    });
});
