<?php

$factory->define(App\Models\Population::class, function (Faker\Generator $faker){
    $population_found = $faker->numberBetween(500, 1500);
    $population_size  = $faker->numberBetween(5000,9000);
    $percentage_found = ($population_found/$population_size) * 100 ;
    return [
        'id' => $faker->unique()->numberBetween(1,1000),
        'population_found' => $population_found,
        'population_size' => $population_size,
        'percentage_found' => round($percentage_found)
    ];
});
