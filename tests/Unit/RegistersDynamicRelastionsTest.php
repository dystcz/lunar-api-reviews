<?php

use Dystcz\LunarApiReviews\Tests\TestCase;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Models\Product;
use Lunar\Models\ProductVariant;

uses(TestCase::class, RefreshDatabase::class);

test('Product has reviews relation', function () {
    $model = new Product();

    expect($model->reviews())->toBeInstanceOf(HasManyThrough::class);
});

test('ProductVariant has reviews relation', function () {
    $model = new ProductVariant();

    expect($model->reviews())->toBeInstanceOf(MorphMany::class);
});
