<?php

use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Dystcz\LunarReviews\Domain\Reviews\Factories\ReviewFactory;
use Dystcz\LunarReviews\Domain\Reviews\Models\Review;
use Dystcz\LunarReviews\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Models\Product;

uses(TestCase::class, RefreshDatabase::class);

it('works', function () {
    $product = \Dystcz\LunarReviews\Tests\Stubs\Products\Product::factory()
        ->has(
            ProductVariantFactory::new()->has(ReviewFactory::new(), 'reviews')->count(3),
            'variants',
        )
        ->create();

    $product = \Dystcz\LunarReviews\Tests\Stubs\Products\Product::query()->withAverageRating()->find($product->id);

    expect($product->average_rating)->toBe((float) $product->reviews->avg('rating'));
});