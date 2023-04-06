<?php

use Dystcz\LunarApiReviews\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

uses(TestCase::class, RefreshDatabase::class);

it('table exists', function () {
    expect(Schema::hasTable('lunar_reviews'))->toBeTrue();
});
