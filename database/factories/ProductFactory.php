<?php

use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    $categories = ProductCategory::lists('id');
    // $categories = ProductCategory::lists('id');
    return [
        $product->category_id = $request->get('categoryId');
        $product->alias = str_slug(mb_convert_encoding($row[2], 'utf8', 'cp1251'));
        $product->stock = mb_convert_encoding($row[8], 'utf8', 'cp1251');
        $product->price = mb_convert_encoding($row[6], 'utf8', 'cp1251');
        $product->discount = mb_convert_encoding($row[7], 'utf8', 'cp1251');
        $product->code = mb_convert_encoding($row[1], 'utf8', 'cp1251');
        $product->stock = mb_convert_encoding($row[8], 'utf8', 'cp1251');
    ];
});
