<?php

use Dystcz\LunarReviews\Tests\TestCase;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Models\ProductVariant;

uses(TestCase::class, RefreshDatabase::class);

test('product variant has reviews relation', function () {
    $variant = new ProductVariant();

    expect($variant->reviews())->toBeInstanceOf(MorphMany::class);
});
