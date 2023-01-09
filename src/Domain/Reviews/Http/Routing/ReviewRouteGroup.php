<?php

namespace Dystcz\LunarReviews\Domain\Reviews\Http\Routing;

use Dystcz\LunarApi\Domain\Products\Http\Controllers\ProductsController;
use Dystcz\LunarApi\Domain\Products\Http\Routing\ProductRouteGroup;
use Dystcz\LunarApi\Routing\RouteGroup;
use Dystcz\LunarReviews\Domain\ProductVariants\Http\Controllers\ProductVariantsController;
use Dystcz\LunarReviews\Domain\Reviews\Http\Controllers\PublishReviewsController;
use Dystcz\LunarReviews\Domain\Reviews\Http\Controllers\ReviewsController;
use Illuminate\Support\Facades\App;
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

                $server->resource(App::make(ProductRouteGroup::class)->getPrefix(), ProductsController::class)
                    ->relationships(function ($relationships) {
                        $relationships->hasMany('reviews')->readOnly();
                    })->only();

                $server->resource('variants', ProductVariantsController::class)
                    ->relationships(function ($relationships) {
                        $relationships->hasMany('reviews')->readOnly();
                    })->only();
            });
    }
}
