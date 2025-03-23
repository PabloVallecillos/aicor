<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Contracts\Factory as Socialite;
use Laravel\Socialite\Two\GoogleProvider;
use Laravel\Socialite\Two\User as SocialiteUser;
use PHPOpenSourceSaver\JWTAuth\JWTAuth;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->socialiteMock = Mockery::mock(Socialite::class);
    $this->googleProviderMock = Mockery::mock(GoogleProvider::class);
    $this->jwtAuthMock = Mockery::mock(JWTAuth::class);

    $this->app->instance(Socialite::class, $this->socialiteMock);
    $this->app->instance(JWTAuth::class, $this->jwtAuthMock);
});

afterEach(function () {
    Mockery::close();
});

test('redirect to google returns socialite redirect response', function () {
    // Prepare
    $this->socialiteMock
        ->shouldReceive('driver')
        ->with('google')
        ->andReturn($this->googleProviderMock);

    $this->googleProviderMock
        ->shouldReceive('stateless')
        ->andReturn($this->googleProviderMock);

    $this->googleProviderMock
        ->shouldReceive('redirect')
        ->andReturn(response('Redirecting to Google'));

    // Act & Assert
    $response = $this->get(route('api.auth.google'));

    $response->assertStatus(status: 200);
    $response->assertSee('Redirecting to Google');
});

test('google callback creates new user when user does not exist', function () {
    // Prepare
    $googleUser = new SocialiteUser;
    $googleUser->id = '123456789';
    $googleUser->name = 'Test User';
    $googleUser->email = 'test@example.com';

    $this->socialiteMock
        ->shouldReceive('driver')
        ->with('google')
        ->andReturn($this->googleProviderMock);

    $this->googleProviderMock
        ->shouldReceive('stateless')
        ->andReturn($this->googleProviderMock);

    $this->googleProviderMock
        ->shouldReceive('user')
        ->andReturn($googleUser);

    $this->jwtAuthMock
        ->shouldReceive('fromUser')
        ->andReturn('test-jwt-token');

    config(['jwt.ttl' => 60]);

    // Act
    $response = $this->get(route('api.auth.google.callback'));

    // Assert
    $response->assertStatus(200);
    $response->assertJson([
        'access_token' => 'test-jwt-token',
        'token_type' => 'bearer',
        'expires_in' => 3600,
    ]);

    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
        'name' => 'Test User',
        'social_id' => '123456789',
        'social_type' => 'google',
    ]);
});

test('google callback updates existing user when user exists', function () {
    // Prepare
    $existingUser = User::factory()->create([
        'email' => 'existing@example.com',
        'name' => 'Existing User',
        'social_id' => null,
        'social_type' => null,
    ]);

    $googleUser = new SocialiteUser;
    $googleUser->id = '123456789';
    $googleUser->name = 'Updated User';
    $googleUser->email = 'existing@example.com';

    $this->socialiteMock
        ->shouldReceive('driver')
        ->with('google')
        ->andReturn($this->googleProviderMock);

    $this->googleProviderMock
        ->shouldReceive('stateless')
        ->andReturn($this->googleProviderMock);

    $this->googleProviderMock
        ->shouldReceive('user')
        ->andReturn($googleUser);

    $this->jwtAuthMock
        ->shouldReceive('fromUser')
        ->andReturn('test-jwt-token');

    config(['jwt.ttl' => 60]);

    // Act
    $response = $this->get(route('api.auth.google.callback'));

    // Assert
    $response->assertStatus(status: 200);
    $response->assertJson([
        'access_token' => 'test-jwt-token',
        'token_type' => 'bearer',
        'expires_in' => 3600,
    ]);

    $this->assertDatabaseHas('users', [
        'id' => $existingUser->id,
        'email' => 'existing@example.com',
        'name' => 'Updated User',
        'social_id' => '123456789',
        'social_type' => 'google',
    ]);
});

test('jwt token is generated with correct ttl', function () {
    // Prepare
    $googleUser = new SocialiteUser;
    $googleUser->id = '123456789';
    $googleUser->name = 'Test User';
    $googleUser->email = 'test@example.com';

    $this->socialiteMock
        ->shouldReceive('driver')
        ->with('google')
        ->andReturn($this->googleProviderMock);

    $this->googleProviderMock
        ->shouldReceive('stateless')
        ->andReturn($this->googleProviderMock);

    $this->googleProviderMock
        ->shouldReceive('user')
        ->andReturn($googleUser);

    $this->jwtAuthMock
        ->shouldReceive('fromUser')
        ->andReturn('test-jwt-token');

    // Set custom TTL
    config(['jwt.ttl' => 120]);

    // Act
    $response = $this->get(route('api.auth.google.callback'));

    // Assert
    $response->assertStatus(status: 200);
    $response->assertJson([
        'access_token' => 'test-jwt-token',
        'token_type' => 'bearer',
        'expires_in' => 7200, // 120 * 60
    ]);
});

test('google callback returns json response with correct structure', function () {
    // Prepare
    $googleUser = new SocialiteUser;
    $googleUser->id = '123456789';
    $googleUser->name = 'Test User';
    $googleUser->email = 'test@example.com';

    $this->socialiteMock
        ->shouldReceive('driver')
        ->with('google')
        ->andReturn($this->googleProviderMock);

    $this->googleProviderMock
        ->shouldReceive('stateless')
        ->andReturn($this->googleProviderMock);

    $this->googleProviderMock
        ->shouldReceive('user')
        ->andReturn($googleUser);

    $this->jwtAuthMock
        ->shouldReceive('fromUser')
        ->andReturn('test-jwt-token');

    config(['jwt.ttl' => 60]);

    // Act
    $response = $this->get(route('api.auth.google.callback'));

    // Assert
    $response->assertStatus(status: 200);
    $response->assertJsonStructure([
        'access_token',
        'token_type',
        'expires_in',
    ]);
});
