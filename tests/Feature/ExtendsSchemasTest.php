<?php

use Dystcz\LunarApi\Domain\Products\Factories\ProductFactory;
use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Dystcz\LunarApiReviews\Domain\Reviews\Factories\ReviewFactory;
use Dystcz\LunarApiReviews\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

it('reviews extend ProductSchema with reviews relationship', function () {
    $product = ProductFactory::new()
        ->has(
            ProductVariantFactory::new()
                ->has(ReviewFactory::new(), 'reviews'),
            'variants'
        )
        ->create();

    $self = "http://localhost/api/v1/variants/{$product->id}/reviews";

    $response = $this
        ->jsonApi()
        ->expects('reviews')
        ->get($self);

    $response->assertFetchedMany($product->reviews);
});

it('reviews extend ProductVariantSchema with reviews relationship', function () {
    $productVariant = ProductVariantFactory::new()
        ->has(ReviewFactory::new(), 'reviews')
        ->create();

    $self = "http://localhost/api/v1/variants/{$productVariant->id}/reviews";

    $response = $this
        ->jsonApi()
        ->expects('reviews')
        ->get($self);

    $response->assertFetchedMany($productVariant->reviews);
});
