<?php

namespace Dystcz\LunarApiReviews\Domain\Reviews\Traits;

use Dystcz\LunarApiReviews\Domain\Reviews\Models\Review;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait InteractsWithReviews
{
    /**
     * Return reviews relation.
     */
    public function reviews(): MorphMany
    {
        /** @var Model $this */
        return $this->morphMany(Review::class, 'purchasable');
    }
}
