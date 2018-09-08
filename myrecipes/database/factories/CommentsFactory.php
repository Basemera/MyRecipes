<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Comments::class, function (Faker $faker){
    $recipes = (App\Models\Recipe::all()->pluck('id'));
    $commentor = (App\Models\User::all()->pluck('id'));
    return [
        'Comment' => $faker->sentence,
        'commentor_id' => $faker->randomElement($commentor),
        'recipe_id' => $faker->randomElement($recipes)
    ];
});