<?php

use Faker\Generator as Faker;
use Illuminate\Database\Seeder;

$factory->define(App\Models\Category::class, function (Faker $faker){
    $users = (App\Models\User::all()->pluck('id'));
    return [
        'name' => $faker->name,
        'description' => $faker->sentence,
        'user_id' => $faker->randomElement($users)
    ];
});