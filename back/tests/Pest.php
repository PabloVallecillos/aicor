<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

use Faker\Factory;

pest()->extend(Tests\TestCase::class)
 // ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function something()
{
    // ..
}

function generateListFilters($class, $custom = []): array
{
    $faker = Factory::create();
    $columns = Illuminate\Support\Facades\Schema::getColumnListing((new $class)->getTable());
    $data['per_page'] = config('resource.listing.per_page');
    $data['link_range'] = 1;
    $data['page'] = 1;
    $data['paginator_mode'] = 0;
    $data['filters'] = $custom;

    if ($faker->numberBetween(0, 1)) {
        $active = $faker->randomElements($columns);

        if ($active) {
            if ($faker->numberBetween(0, 1)) {
                $data['hide_fields'] = $active;
            } else {
                $data['only_fields'] = $active;
            }
        }
    }

    if ($faker->numberBetween(0, 1)) {
        $orderField = array_values(array_slice($columns, array_rand($columns), 1))[0];
        $data['order'] = [$orderField => ($faker->numberBetween(0, 1)) ? 'ASC' : 'DESC'];
    }

    return $data;
}
