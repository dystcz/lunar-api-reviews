<?php

namespace Dystcz\LunarReviews\Tests\Stubs\Products;

use Dystcz\LunarReviews\Domain\Reviews\Models\Concerns\WithAverageRating;
use Lunar\Database\Factories\ProductFactory;

class Product extends \Lunar\Models\Product
{
    use WithAverageRating;

    /**
     * Return a new factory instance for the model.
     *
     * @return \Lunar\Database\Factories\ProductFactory
     */
    protected static function newFactory(): ProductFactory
    {
        return ProductFactory::new();
    }
}