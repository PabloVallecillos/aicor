<?php

use App\Models\Order;
use App\Models\User;
use Database\Seeders\OrderSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('tests the index route', function () {
    $this->seed(OrderSeeder::class);
    $this->actingAs($this->user, 'api');

    $headers = [
        'Accept' => 'application/json',
    ];

    $data = generateListFilters(Order::class);

    $response = $this->post(route('api.orders.list'), $data, $headers);

    $response->assertOk();

    $content = json_decode($response->content(), true);

    expect($content)->toHaveKey('data');
});

it('ensure auth orders', function () {
    Order::factory()->count(3)->create(['user_id' => $this->user->id]);
    Order::factory()->count(2)->create();
    $this->actingAs($this->user, 'api');

    $headers = [
        'Accept' => 'application/json',
    ];

    $data = generateListFilters(Order::class);

    $response = $this->post(route('api.orders.list'), $data, $headers);

    $response->assertOk();

    $content = json_decode($response->content(), true);

    expect($content)
        ->toHaveKey('data')
        ->and(count($content['data']))->toBeGreaterThanOrEqual(3)
        ->and($content['data'][0]['user_id'])->toEqual($this->user->id);
});

it('ensure unauthenticated users cannot filter orders', function () {
    $headers = [
        'Accept' => 'application/json',
    ];

    $data = generateListFilters(Order::class);

    $response = $this->post(route('api.orders.list'), $data, $headers);

    $response->assertForbidden();
});
