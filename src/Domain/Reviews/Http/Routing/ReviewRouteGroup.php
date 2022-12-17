<?php

namespace Dystcz\LunarReviews\Domain\Reviews\Http\Routing;

use Dystcz\LunarReviews\Domain\Reviews\Http\Controllers\PublishReviewsController;
use Dystcz\LunarReviews\Domain\Reviews\Http\Controllers\ReviewsController;
use Dystcz\LunarReviews\Routing\RouteGroup;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;

class ReviewRouteGroup extends RouteGroup
{
    /** @var string */
    public string $prefix = 'reviews';

    /** @var array */
    public array $middleware = [];

    /**
     * Register routes.
     *
     * @param  null|string  $prefix
     * @param  array|string  $middleware
     * @return void
     */
    public function routes(?string $prefix = null, array|string $middleware = []): void
    {
        JsonApiRoute::server('v1')
            ->prefix('v1')
            ->resources(function ($server) {
                $server->resource($this->getPrefix(), ReviewsController::class)
                    ->except('update');

                $server->resource($this->getPrefix(), PublishReviewsController::class)
                    ->only('')
                    ->actions('-actions', function ($actions) {
                        $actions->withId()->post('publish');
                        $actions->withId()->delete('unpublish');
                    });
            });
    }
}
