<?php

namespace Dystcz\LunarReviews;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Dystcz\LunarReviews\Skeleton\SkeletonClass
 */
class LunarReviewsFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'lunar-reviews';
    }
}
