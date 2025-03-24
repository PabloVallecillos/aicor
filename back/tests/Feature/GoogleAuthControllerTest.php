<?php

use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use PHPOpenSourceSaver\JWTAuth\JWTAuth;

beforeEach(function () {
    $this->jwt = mock(JWTAuth::class);
    $this->app->instance(JWTAuth::class, $this->jwt);
});

it('authenticates a user with a valid Google token', function () {
    // Prepare
    $googleUserId = '123456789';
    $googleUserEmail = 'test@example.com';
    $googleUserName = 'Test User';
    $accessToken = 'valid_mocked_google_token';
    $socialiteUser = new SocialiteUser;
    $socialiteUser->id = $googleUserId;
    $socialiteUser->email = $googleUserEmail;
    $socialiteUser->name = $googleUserName;

    Http::fake([
        'https://oauth2.googleapis.com/tokeninfo*' => Http::response([
            'aud' => config('services.google.client_id'),
            'sub' => $googleUserId,
            'email' => $googleUserEmail,
            'email_verified' => true,
        ]),
    ]);

    Socialite::shouldReceive('driver->stateless->userFromToken')
        ->with($accessToken)
        ->andReturn($socialiteUser);

    $this->mock(JWTAuth::class, function ($mock) {
        $mock->shouldReceive('fromUser')->andReturn('mocked_jwt_token');
    });

    // Act
    $response = $this->postJson(route('api.auth.google'), [
        'access_token' => $accessToken,
    ]);

    // Assert
    $response->assertStatus(200)
        ->assertJsonStructure([
            'access_token',
            'token_type',
            'expires_in',
        ]);

    $this->assertDatabaseHas('users', [
        'email' => $googleUserEmail,
        'name' => $googleUserName,
        'social_id' => $googleUserId,
        'social_type' => 'google',
    ]);
});

it('returns an error when the token audience is invalid', function () {
    $accessToken = 'valid_token_wrong_audience';

    Http::fake([
        'https://oauth2.googleapis.com/tokeninfo' => Http::response([
            'aud' => 'wrong_client_id',
            'email' => 'test@example.com',
        ], 200),
    ]);

    $response = $this->postJson(route('api.auth.google'), [
        'access_token' => $accessToken,
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['access_token']);
});

it('returns an error when the token does not contain an email', function () {
    $accessToken = 'valid_token_no_email';

    Http::fake([
        'https://oauth2.googleapis.com/tokeninfo' => Http::response([
            'aud' => config('services.google.client_id'),
        ]),
    ]);

    $response = $this->postJson(route('api.auth.google'), [
        'access_token' => $accessToken,
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['access_token']);
});
