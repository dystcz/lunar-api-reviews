<?php

use Dystcz\LunarReviews\Domain\Reviews\Factories\ReviewFactory;
use Dystcz\LunarReviews\Tests\Stubs\Users\UserFactory;
use Dystcz\LunarReviews\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Database\Factories\ProductVariantFactory;
use Lunar\Hub\Database\Factories\StaffFactory;

uses(TestCase::class, RefreshDatabase::class);

it('can publish a review', function () {
    $user = UserFactory::new()->create();

    $review = ReviewFactory::new()
        ->for(ProductVariantFactory::new(), 'purchasable')
        ->create([
            'published_at' => null,
        ]);

    $self = "http://localhost/api/v1/reviews/{$review->getRouteKey()}/-actions/publish";

    $response = $this
        ->actingAs($user)
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
    $user = StaffFactory::new()->create(['admin' => true]);

    $review = ReviewFactory::new()
        ->for(ProductVariantFactory::new(), 'purchasable')
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
