<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'username' => 'phiona',
            'email' => 'phiona@g.com',
            'password' => Hash::make('phiona')
        ]);
        factory(App\Models\User::class, 50)->create()->each(function ($user) {
            $user->categories()->save(factory(App\Models\Category::class)->make());
        });
    }
}
