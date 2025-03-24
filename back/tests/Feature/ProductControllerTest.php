<?php

use App\Models\Product;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('tests the index route', function () {
    $this->seed(ProductSeeder::class);

    $headers = [
        'Accept' => 'application/json',
    ];

    $data = generateListFilters(Product::class);

    $response = $this->post(route('products.list'), $data, $headers);

    $response->assertOk();

    $content = json_decode($response->content(), true);

    expect($content)->toHaveKey('data');
});
