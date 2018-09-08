<?php

use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;

$factory->define(App\Models\User::class, function (Faker $faker){
   return [
       'username' => $faker->userName,
       'email' => $faker->unique()->safeEmail,
       'password' => Hash::make('secret'),
   ];
});