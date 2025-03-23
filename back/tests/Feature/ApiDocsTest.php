<?php

use function Pest\Laravel\getJson;

it('returns a valid JSON with openapi 3.1.0', function () {
    $response = getJson('/docs/api.json');

    // TODO: change when authentication is implemented
    $response->assertStatus(status:403);
});
