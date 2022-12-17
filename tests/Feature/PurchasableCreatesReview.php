<?php

use Dystcz\LunarReviews\Domain\Reviews\Factories\ReviewFactory;
use Dystcz\LunarReviews\Tests\Stubs\Users\UserFactory;
use Dystcz\LunarReviews\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Database\Factories\ProductVariantFactory;

uses(TestCase::class, RefreshDatabase::class);

it('can as purchasable read reviews', function () {
    $user = UserFactory::new()->create();

    $review = ReviewFactory::new()
        ->for(ProductVariantFactory::new(), 'purchasable')
        ->create();

    $response = $this
        ->actingAs($user)
        ->jsonApi()
        ->expects('reviews')
        ->get('/api/v1/variants/'.$review->purchasable->getRouteKey().'/reviews');

    $response->assertFetchedMany([$review]);
});
