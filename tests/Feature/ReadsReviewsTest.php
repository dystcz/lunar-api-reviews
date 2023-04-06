<?php

use Dystcz\LunarApiReviews\Domain\Reviews\Factories\ReviewFactory;
use Dystcz\LunarApiReviews\Domain\Reviews\Models\Review;
use Dystcz\LunarApiReviews\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Database\Factories\ProductVariantFactory;

uses(TestCase::class, RefreshDatabase::class);

it('reads reviews', function () {
    $reviews = ReviewFactory::new()
        ->for(ProductVariantFactory::new(), 'purchasable')
        ->count(5)
        ->create();

    $self = 'http://localhost/api/v1/reviews';

    $response = $this
        ->jsonApi()
        ->expects('reviews')
        ->get($self);

    $response->assertFetchedMany($reviews);
});

it('reads a review', function () {
    /** @var Review $review */
    $review = ReviewFactory::new()
        ->for(ProductVariantFactory::new(), 'purchasable')
        ->create();

    $self = 'http://localhost/api/v1/reviews/'.$review->getRouteKey();

    $response = $this
        ->jsonApi()
        ->expects('reviews')
        ->get($self);

    $response->assertFetchedOne($review);
});
