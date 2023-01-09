<?php

use Dystcz\LunarReviews\Domain\Reviews\Factories\ReviewFactory;
use Dystcz\LunarReviews\Tests\Stubs\Users\UserFactory;
use Dystcz\LunarReviews\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Database\Factories\ProductVariantFactory;
use Lunar\Hub\Database\Factories\StaffFactory;

uses(TestCase::class, RefreshDatabase::class);

it('deletes a review', function () {
    $user = UserFactory::new()->create();

    $review = ReviewFactory::new()
        ->for(ProductVariantFactory::new(), 'purchasable')
        ->for($user)
        ->create();

    $response = $this
        ->actingAs($user)
        ->jsonApi()
        ->delete('/api/v1/reviews/'.$review->getKey());

    $response->assertNoContent();

    $this->assertDatabaseMissing($review->getTable(), [
        'id' => $review->getKey(),
    ]);
});

it('allows only admin or owner to delete a review', function () {
    // delete review as random user
    $randomUser = UserFactory::new()->create();
    $reviewOwner = UserFactory::new()->create();

    $review = ReviewFactory::new()
        ->for(ProductVariantFactory::new(), 'purchasable')
        ->for($reviewOwner)
        ->create();

    $response = $this
        ->actingAs($randomUser)
        ->jsonApi()
        ->delete('/api/v1/reviews/'.$review->getRouteKey());
    
    $response->assertErrorStatus([
        'detail' => 'This action is unauthorized.',
        'status' => '403',
        'title' => 'Forbidden',
    ]);

    // delete review as admin
    $adminUser = StaffFactory::new()->create(['admin' => true]);

    $response = $this
        ->actingAs($adminUser)
        ->jsonApi()
        ->delete('/api/v1/reviews/'.$review->getRouteKey());

    $response->assertNoContent();
});
