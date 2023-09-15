<?php

use Dystcz\LunarApiReviews\Domain\Reviews\JsonApi\V1\ReviewSchema;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => Config::get('lunar-api-reviews.route_prefix'),
    'middleware' => Config::get('lunar-api-reviews.route_middleware'),
], function (Router $router) {
    foreach (Arr::flatten(Arr::pluck(Config::get('lunar-api-reviews.domains'), 'route_groups')) as $key => $group) {
        $group::make(ReviewSchema::type())->routes();
    }
});
