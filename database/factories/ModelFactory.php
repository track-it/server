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
