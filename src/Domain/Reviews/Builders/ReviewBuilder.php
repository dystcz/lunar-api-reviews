<?php

namespace Dystcz\LunarApiReviews\Domain\Reviews\Builders;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

/**
 * @method self published()
 */
class ReviewBuilder extends Builder
{
    /**
     * Scope a query to only include published models.
     */
    public function published(): self
    {
        return $this
            ->where('published_at', '!=', null)
            ->where('published_at', '<=', Carbon::now());
    }
}
