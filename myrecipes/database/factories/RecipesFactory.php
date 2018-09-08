<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Recipe::class, function (Faker $faker){
    $recipes = (App\Models\Category::all()->pluck('id'));
    return [
        'name' => $faker->name,
        'description' => $faker->sentence,
        'category_id' => $faker->randomElement($recipes)
    ];
});