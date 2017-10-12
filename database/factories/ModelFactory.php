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

$factory->define(App\Entities\ChatChannel::class, function (Faker\Generator $faker) {

    $user_id = $faker->numberBetween(5,54);

    return [
        'name' => $faker->company,
        'user_id' => $user_id,
        'updated_by' => $user_id
    ];

});

/*chat thread*/
$factory->define(App\Entities\ChatThread::class, function (Faker\Generator $faker) {

    $user_id = $faker->numberBetween(5,54);

    return [
        'title' => $faker->sentence,
        'chat_channel_id' => $faker->numberBetween(1,20),
        'user_id' => $user_id,
        'updated_by' => $user_id
    ];

});

/*chat messages*/
$factory->define(App\Entities\ChatMessage::class, function (Faker\Generator $faker) {

    $user_id = $faker->numberBetween(5,54);

    return [
        'chat_text' => $faker->paragraph,
        'user_id' => $user_id,
        'chat_thread_id' => $faker->numberBetween(1,100)
    ];

});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Entities\User::class, function (Faker\Generator $faker) {
    
    return [
        'uuid' => Uuid::generate(),
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'gender' => $faker->randomElement($array = array ('m','f')),
        'email' => $faker->unique()->safeEmail,
        'phone' => "07" . $faker->numberBetween(10,29) . $faker->numberBetween(100000,999999),
        'preferred_amount' => $faker->randomElement($array = array ('500', '1000', '2000', '3000', '4000', '5000', '6000', '7000', '8000')),
        'phone_country' => $faker->randomElement($array = array ('KE', 'US', 'UG', 'TZ', 'UK')),
        'password' => bcrypt('123456'),
        'active' => '1',
        'confirm_code' => strtoupper(generateCode(5)),
        'src_ip' => $faker->ipv4,
        'user_agent' => $faker->userAgent,
        'created_by' => 1,
        'updated_by' => 1,
    ];
    
});
