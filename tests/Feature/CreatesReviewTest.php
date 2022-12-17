<?php

use Dystcz\LunarReviews\Domain\Reviews\Factories\ReviewFactory;
use Dystcz\LunarReviews\Domain\Reviews\Models\Review;
use Dystcz\LunarReviews\Tests\Stubs\Users\UserFactory;
use Dystcz\LunarReviews\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Database\Factories\ProductVariantFactory;

uses(TestCase::class, RefreshDatabase::class);

it('creates a review', function () {
    $user = UserFactory::new()->create();

    /** @var Review $review */
    $review = ReviewFactory::new()
        ->for(ProductVariantFactory::new(), 'purchasable')
        ->make();

    $data = [
        'type' => 'reviews',
        'attributes' => [
            'comment' => $review->comment,
            'rating' => $review->rating,
            'purchasable_id' => $review->purchasable_id,
            'purchasable_type' => $review->purchasable_type,
        ],
    ];

    $response = $this
        ->actingAs($user)
        ->jsonApi()
        ->expects('reviews')
        ->withData($data)
        ->post('/api/v1/reviews');

    $id = $response
        ->assertCreatedWithServerId('http://localhost/api/v1/reviews', $data)
        ->id();

    $this->assertDatabaseHas($review->getTable(), [
        'id' => $id,
        'user_id' => $review->user_id,
        'purchasable_id' => $review->purchasable_id,
        'purchasable_type' => $review->purchasable_type,
        'comment' => $review->comment,
        'rating' => $review->rating,
    ]);
});
