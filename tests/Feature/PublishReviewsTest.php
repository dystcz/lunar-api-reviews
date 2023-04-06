<?php

use Dystcz\LunarApi\Domain\ProductVariants\Models\ProductVariant;
use Dystcz\LunarApiReviews\Domain\Reviews\Models\Review;
use Dystcz\LunarApiReviews\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Hub\Models\Staff;

uses(TestCase::class, RefreshDatabase::class);

it('can publish a review', function () {
    $review = Review::factory()
        ->for(ProductVariant::factory(), 'purchasable')
        ->create([
            'published_at' => null,
        ]);

    $self = "http://localhost/api/v1/reviews/{$review->getRouteKey()}/-actions/publish";

    $response = $this
        ->actingAs($review->user)
        ->jsonApi()
        ->expects('reviews')
        ->post($self);

    $response->assertFetchedOne($review);

    expect(
        $response->json('data.attributes.published_at')
    )
        ->toBe($review->fresh()->published_at->toIsoString());
});

it('can unpublish a review', function () {
    $user = Staff::factory()->create(['admin' => true]);

    $review = Review::factory()
        ->for(ProductVariant::factory(), 'purchasable')
        ->create();

    $self = "http://localhost/api/v1/reviews/{$review->getRouteKey()}/-actions/unpublish";

    $response = $this
        ->actingAs($user)
        ->jsonApi()
        ->expects('reviews')
        ->delete($self);

    $response->assertNoContent();

    expect($review->fresh()->published_at)->toBe(null);
});
