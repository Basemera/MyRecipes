<?php

use Faker\Generator as Faker;

$factory->define(App\Models\User::class, function (Faker $faker){
   return [
       'username' => $faker->userName,
       'email' => $faker->unique()->safeEmail,
       'password' => $faker->password,
   ];
});