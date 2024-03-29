<?php

use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Domain\ProductVariants\Models\ProductVariant;
use Dystcz\LunarApiReviews\Domain\Reviews\Models\Review;
use Dystcz\LunarApiReviews\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

it('reviews extend "ProductSchema" with reviews relationship', function () {
    /** @var TestCase $this */
    $product = Product::factory()
        ->has(
            ProductVariant::factory()
                ->has(
                    Review::factory()->count(3),
                    'reviews',
                ),
            'variants'
        )
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('reviews')
        ->get("/api/v1/products/{$product->getRouteKey()}/reviews");

    $response->assertFetchedMany($product->reviews);
})->todo();

it('reviews extend "ProductVariantSchema" with reviews relationship', function () {
    /** @var TestCase $this */
    $productVariant = ProductVariant::factory()
        ->has(
            Review::factory()->count(3),
            'reviews',
        )
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('reviews')
        ->get("/api/v1/variants/{$productVariant->getRouteKey()}/reviews");

    $response->assertFetchedMany($productVariant->reviews);
})->todo();
