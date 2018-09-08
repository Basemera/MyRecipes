<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Comments::class, function (Faker $faker){
    $recipes = (App\Models\Recipe::all()->pluck('id'));
    $commentor = (App\Models\Category::all()->pluck('id'));
    return [
        'Comment' => $faker->sentence,
        'commentor' => $faker->randomElement($commentor),
        'recipe_id' => $faker->randomElement($recipes)
    ];
});