<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use PHPOpenSourceSaver\JWTAuth\JWTAuth;

class GoogleAuthController extends Controller
{
    protected JWTAuth $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::updateOrCreate(
            ['email' => $googleUser->email],
            [
                'name' => $googleUser->name,
                'social_id' => $googleUser->id,
                'social_type' => 'google',
                'password' => bcrypt(uniqid()),
            ]
        );

        $token = $this->jwt->fromUser($user);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60,
        ]);
    }
}
