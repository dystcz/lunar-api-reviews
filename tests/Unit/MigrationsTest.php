<?php

use Dystcz\LunarReviews\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

it('table exists', function () {
    expect(\Schema::hasTable('lunar_product_reviews'))->toBeTrue();
});
