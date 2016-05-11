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
        'displayname' => $faker->name,
        'email' => $faker->email,
        'role_id' => Trackit\Models\Role::byName('teacher')->first()->id,
    ];
});

$factory->define(Trackit\Models\Proposal::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->sentence(3, true),
        'description' => $faker->paragraph(6, true),
        'status' => collect(Trackit\Models\Proposal::STATUSES)->random(),
        'author_id' => factory(Trackit\Models\User::class)->create()->id,
        'created_at' => $faker->dateTimeThisMonth(),
    ];
});

$factory->define(Trackit\Models\Project::class, function (Faker\Generator $faker) {
    return [
        // 'proposal_id' => factory(Trackit\Models\Proposal::class)->create()->id,
        'title' => $faker->sentence(6, true),
        'status' => collect(Trackit\Models\Project::STATUSES)->random(),
        'team_id' => factory(Trackit\Models\Team::class)->create()->id,
    ];
});

$factory->define(Trackit\Models\Comment::class, function (Faker\Generator $faker) {
    return [
        'body' => $faker->sentence,
    ];
});

$factory->define(Trackit\Models\Attachment::class, function (Faker\Generator $faker) {
    $filename = $faker->sentence(3, true);
    return [
        'title' => $filename,
        'path' => storage_path($filename),
    ];
});

$factory->define(Trackit\Models\Tag::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word,
    ];
});

$factory->define(Trackit\Models\Course::class, function (Faker\Generator $faker) {
    return [

    ];
});

$factory->define(Trackit\Models\Workflow::class, function (Faker\Generator $faker) {
    return [

    ];
});

$factory->define(Trackit\Models\ProjectUser::class, function (Faker\Generator $faker) {
    return [
        'user_id' => factory(Trackit\Models\User::class)->create()->id,
        'project_id' => factory(Trackit\Models\Project::class)->create()->id,
        'project_role_id' => Trackit\Models\ProjectRole::all()->random(1)->id,
    ];
});

$factory->define(Trackit\Models\Team::class, function (Faker\Generator $faker) {
    return [

    ];
});
