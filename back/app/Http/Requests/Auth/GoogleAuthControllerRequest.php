<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Validator;

class GoogleAuthControllerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'access_token' => [
                'required',
                'string',
            ],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $token = $this->get('access_token');

                if ($token) {
                    $googleResponse = Http::get('https://oauth2.googleapis.com/tokeninfo', [
                        'access_token' => $token,
                    ]);

                    if (! $googleResponse->successful()) {
                        $validator->errors()->add(
                            'access_token',
                            __('Invalid token: '.($googleResponse->json('error_description')) ?? __('Token validation failed'))
                        );

                        return;
                    }

                    $payload = $googleResponse->json();

                    if (! isset($payload['aud']) || $payload['aud'] !== config('services.google.client_id')) {
                        $validator->errors()->add('access_token', __('Invalid audience (aud).'));
                    }

                    if (! isset($payload['email'])) {
                        $validator->errors()->add('access_token', __('Token does not contain email information.'));
                    }
                }
            },
        ];
    }
}
