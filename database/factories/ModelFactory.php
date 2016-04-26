<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/


$factory->define(Trackit\Models\Role::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word,
    ];
});


$factory->define(Trackit\Models\User::class, function (Faker\Generator $faker) {
    return [
        'username' => $faker->name,
    ];
});

$factory->define(Trackit\Models\Proposal::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->sentence(3, true),
        'description' => $faker->paragraph(6, true),
        'status' => collect(Trackit\Models\Proposal::STATUSES)->random(),
        'user_id' => factory(Trackit\Models\User::class)->create()->id,
    ];
});


$factory->define(Trackit\Models\Comment::class, function (Faker\Generator $faker) {
    return [
        'body' => $faker->sentence,
    ];
});

$factory->define(Trackit\Models\Attachment::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->sentence(3, true),
        'url' => $faker->url(),
    ];
});

$factory->define(Trackit\Models\Tag::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word,
    ];
});
