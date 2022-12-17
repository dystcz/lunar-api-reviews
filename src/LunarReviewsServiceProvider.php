<?php

namespace Dystcz\LunarReviews;

use Dystcz\LunarReviews\Domain\Reviews\Models\Review;
use Dystcz\LunarReviews\Domain\Reviews\Policies\ReviewPolicy;
use Illuminate\Support\ServiceProvider;
use Lunar\Models\ProductVariant;

class LunarReviewsServiceProvider extends ServiceProvider
{
    protected array $policies = [
        Review::class => ReviewPolicy::class,
    ];

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Register routes
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');

        $this->dynamicRelations();

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/lunar-reviews.php' => config_path('lunar-reviews.php'),
            ], 'config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/lunar-reviews'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/lunar-reviews'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/lunar-reviews'),
            ], 'lang');*/

            // Registering package commands.
            // $this->commands([]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/lunar-reviews.php', 'lunar-reviews');
        $this->mergeConfigFrom(__DIR__.'/../config/jsonapi.php', 'jsonapi');

        // Register the main class to use with the facade
        $this->app->singleton('lunar-reviews', function () {
            return new LunarReviews;
        });
    }

    protected function dynamicRelations(): void
    {
        ProductVariant::resolveRelationUsing('reviews', function ($model) {
            return $model->morphMany(Review::class, 'purchasable');
        });
    }
}
