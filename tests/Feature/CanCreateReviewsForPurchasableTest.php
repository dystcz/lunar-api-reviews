<?php

use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Domain\ProductVariants\Models\ProductVariant;
use Dystcz\LunarApiReviews\Domain\Reviews\Models\Review;
use Dystcz\LunarApiReviews\Tests\Stubs\Users\User;
use Dystcz\LunarApiReviews\Tests\Stubs\Users\UserFactory;
use Dystcz\LunarApiReviews\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;

uses(TestCase::class, RefreshDatabase::class);

it('requires logged in user to create a review', function () {
    /** @var TestCase $this */

    /** @var Review $review */
    $review = Review::factory()
        ->for(Product::factory(), 'purchasable')
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
        ->jsonApi()
        ->expects('reviews')
        ->withData($data)
        ->post('/api/v1/reviews');

    $response->assertErrorStatus([
        'detail' => 'Unauthenticated.',
        'status' => '401',
        'title' => 'Unauthorized',
    ]);
});

it('can store anonymous review when configured', function () {
    /** @var TestCase $this */
    Config::get('lunar-api.reviews.domains.reviews.settings.auth_required', false);

    /** @var Review $review */
    $review = Review::factory()
        ->for(Product::factory(), 'purchasable')
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
        ->jsonApi()
        ->expects('reviews')
        ->withData($data)
        ->post('/api/v1/reviews');

    $id = $response
        ->assertCreatedWithServerId('http://localhost/api/v1/reviews', $data)
        ->id();

    $this->assertDatabaseHas($review->getTable(), [
        'id' => $id,
        'user_id' => null,
        'purchasable_id' => $review->purchasable_id,
        'purchasable_type' => $review->purchasable_type,
        'comment' => $review->comment,
        'rating' => $review->rating,
    ]);
})->todo();

it('can create a review for a product', function () {
    /** @var TestCase $this */

    /** @var User $user */
    $user = UserFactory::new()->create();

    /** @var Review $review */
    $review = Review::factory()
        ->for(Product::factory(), 'purchasable')
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
        'user_id' => $user->id,
        'purchasable_id' => $review->purchasable_id,
        'purchasable_type' => $review->purchasable_type,
        'comment' => $review->comment,
        'rating' => $review->rating,
    ]);
});

it('can create a review for a product variant', function () {
    /** @var TestCase $this */

    /** @var User $user */
    $user = UserFactory::new()->create();

    /** @var Review $review */
    $review = Review::factory()
        ->for(ProductVariant::factory(), 'purchasable')
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
        'user_id' => $user->id,
        'purchasable_id' => $review->purchasable_id,
        'purchasable_type' => $review->purchasable_type,
        'comment' => $review->comment,
        'rating' => $review->rating,
    ]);
});
