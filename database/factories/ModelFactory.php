<?php

use App\Category;
use App\Product;
use App\Transaction;
use App\User;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => bcrypt('secret'),
        'remember_token' => str_random(10),
        'verified' => $faker->randomElement([true, false]),
        'verify_token' => str_random(60),
        'admin' => $faker->randomElement([true, false]),
    ];
});

$factory->define(Category::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word(1),
        'description' => $faker->paragraph(1)
    ];
});

$factory->define(Product::class, function (Faker $faker) {
    $sellerIds = User::where('admin', '=', true)->pluck('id');
    return [
        'name' => $faker->name,
        'description' => $faker->paragraph(1),
        'quantity' => mt_rand(1, 10),
        'image' => $faker->randomElement(['1.jpg', '2.jpg', '3.jpg', '4.jpg']),
        'status' => $faker->randomElement([1, 2, 3, 4]),
        'seller_id' => $faker->randomElement($sellerIds->toArray())
    ];
});

$factory->define(Transaction::class, function (Faker $faker) {
    $productIds = Product::all()->random(mt_rand(1, 5))->pluck('id');
    $buyerIds = User::where('admin', '=', false)->pluck('id');
    return [
        'quantity' => mt_rand(1, 10),
        'product_id' => $faker->randomElement($productIds->toArray()),
        'buyer_id' => $faker->randomElement($buyerIds->toArray())
    ];
});
