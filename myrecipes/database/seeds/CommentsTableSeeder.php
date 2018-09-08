<?php

use Illuminate\Database\Seeder;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        dd(App\Models\Recipe::all());
        factory(App\Models\Comments::class, 50)->create()->each(function ($user) {
        });
    }
}
