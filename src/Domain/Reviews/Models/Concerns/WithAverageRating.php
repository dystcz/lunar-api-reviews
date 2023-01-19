<?php

namespace Dystcz\LunarReviews\Domain\Reviews\Models\Concerns;

use Lunar\Models\ProductVariant;
use Dystcz\LunarReviews\Domain\Reviews\Models\Review;
use Illuminate\Database\Eloquent\Builder;
use Lunar\Models\Product;

trait WithAverageRating
{
    /**
     * Calculate the average rating from the reviews for the product.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeWithAverageRating(Builder $query): Builder
    {
        $productVariantTable = (new ProductVariant)->getTable();
        $productTable = (new Product)->getTable();
        $reviewTable = (new Review)->getTable();

        return $query->addSelect([
            'average_rating' => Review::selectRaw('avg(rating)')
                ->join($productVariantTable, $productVariantTable.'.id', '=', $reviewTable.'.purchasable_id')
                ->whereColumn($productVariantTable.'.product_id', $productTable.'.id')
                ->where($reviewTable.'.purchasable_type', ProductVariant::class)
                ->limit(1),
        ]);
    }
}