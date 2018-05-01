<?php

use Faker\Generator as Faker;

$factory->define(App\Post::class, function (Faker $faker) {
    return [
       'user_id' => App\User::all()->random()->id,
       'post_title' => $faker->name,
       'post_body' => $faker->text,
       'post_img' => '1.jpg'
    ];
});
