<?php

namespace Dystcz\LunarApiReviews;

use Dystcz\LunarApi\Domain\JsonApi\Extensions\Resource\ResourceManifest;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Schema\SchemaManifest;
use Dystcz\LunarApi\Domain\Products\JsonApi\V1\ProductResource;
use Dystcz\LunarApi\Domain\Products\JsonApi\V1\ProductSchema;
use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Domain\ProductVariants\JsonApi\V1\ProductVariantResource;
use Dystcz\LunarApi\Domain\ProductVariants\JsonApi\V1\ProductVariantSchema;
use Dystcz\LunarApi\Domain\ProductVariants\Models\ProductVariant;
use Dystcz\LunarApiReviews\Domain\Hub\Components\Slots\ReviewsSlot;
use Dystcz\LunarApiReviews\Domain\Reviews\Models\Review;
use Dystcz\LunarApiReviews\Domain\Reviews\Policies\ReviewPolicy;
use Illuminate\Support\ServiceProvider;
use LaravelJsonApi\Eloquent\Fields\Relations\HasMany;
use LaravelJsonApi\Eloquent\Fields\Relations\HasManyThrough;
use Livewire\Livewire;
use Lunar\Hub\Facades\Slot;

class LunarReviewsServiceProvider extends ServiceProvider
{
    protected $policies = [
        Review::class => ReviewPolicy::class,
    ];

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadViewsFrom(__DIR__.'/Domain/Hub/resources/views', 'lunar-reviews');
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');

        Livewire::component('lunar-reviews::reviews-slot', ReviewsSlot::class);

        Slot::register('product.show', ReviewsSlot::class);

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/lunar-reviews.php' => config_path('lunar-reviews.php'),
            ], 'config');

            $this->publishes([
                __DIR__.'/Domain/Hub/resources/views' => resource_path('views/vendor/lunar-reviews'),
            ], 'views');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/lunar-reviews.php', 'lunar-reviews');

        $this->registerDynamicRelations();
        $this->extendSchemas();
    }

    protected function registerDynamicRelations(): void
    {
        ProductVariant::resolveRelationUsing('reviews', function ($model) {
            return $model->morphMany(Review::class, 'purchasable');
        });

        Product::resolveRelationUsing('reviews', function ($model) {
            return $model->hasManyThrough(
                Review::class,
                ProductVariant::class,
                'product_id',
                'purchasable_id'
            )
                ->where(
                    'purchasable_type',
                    ProductVariant::class
                );
        });
    }

    protected function extendSchemas(): void
    {
        SchemaManifest::for(ProductSchema::class)
            ->includePaths(['reviews', 'variants.reviews'])
            ->fields([
                HasManyThrough::make('reviews'),
            ])
            ->showRelated(['reviews'])
            ->showRelationships(['reviews']);

        ResourceManifest::for(ProductResource::class)
            ->relationships(fn ($resource) => [
                $resource->relation('reviews'),
            ]);

        ResourceManifest::for(ProductResource::class)
            ->relationships(fn ($resource) => [
                $resource->relation('reviews'),
            ]);

        SchemaManifest::for(ProductVariantSchema::class)
            ->includePaths(['reviews'])
            ->fields([
                HasMany::make('reviews'),
            ])
            ->showRelated(['reviews'])
            ->showRelationships(['reviews']);

        ResourceManifest::for(ProductVariantResource::class)
            ->relationships(fn ($resource) => [
                'reviews' => $resource->relation('reviews'),
            ]);
    }
}
