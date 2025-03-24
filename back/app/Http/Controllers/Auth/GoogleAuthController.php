<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\GoogleAuthControllerRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Laravel\Socialite\Facades\Socialite;
use PHPOpenSourceSaver\JWTAuth\JWTAuth;

class GoogleAuthController extends Controller
{
    public function __construct(protected JWTAuth $jwt) {}

    public function __invoke(GoogleAuthControllerRequest $request): JsonResponse
    {
        $googleUser = Socialite::driver('google')
            ->stateless()
            ->userFromToken($request->access_token);

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
