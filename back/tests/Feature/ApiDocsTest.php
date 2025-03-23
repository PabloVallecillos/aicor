<?php

use function Pest\Laravel\getJson;

it('returns a valid JSON with openapi 3.1.0', function () {
    $response = getJson('/docs/api.json');

    $response->assertOk()
        ->assertHeader('Content-Type', 'application/json')
        ->assertJsonPath('openapi', '3.1.0');
});
