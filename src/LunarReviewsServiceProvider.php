<?php

namespace Dystcz\LunarApiReviews;

use Dystcz\LunarApi\Base\Contracts\ResourceManifest;
use Dystcz\LunarApi\Base\Contracts\SchemaManifest;
use Dystcz\LunarApi\Base\Extensions\ResourceExtension;
use Dystcz\LunarApi\Base\Extensions\SchemaExtension;
use Dystcz\LunarApi\Base\Facades\SchemaManifestFacade;
use Dystcz\LunarApi\Domain\Products\JsonApi\V1\ProductResource;
use Dystcz\LunarApi\Domain\Products\JsonApi\V1\ProductSchema;
use Dystcz\LunarApi\Domain\ProductVariants\JsonApi\V1\ProductVariantResource;
use Dystcz\LunarApi\Domain\ProductVariants\JsonApi\V1\ProductVariantSchema;
use Dystcz\LunarApi\Support\Config\Collections\DomainConfigCollection;
use Dystcz\LunarApiReviews\Domain\Hub\Components\Slots\ReviewsSlot;
use Dystcz\LunarApiReviews\Domain\Reviews\JsonApi\V1\ReviewSchema;
use Dystcz\LunarApiReviews\Domain\Reviews\Models\Review;
use Dystcz\LunarApiReviews\Domain\Reviews\Observers\ReviewObserver;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use LaravelJsonApi\Eloquent\Fields\Number;
use LaravelJsonApi\Eloquent\Fields\Relations\HasMany;
use LaravelJsonApi\Eloquent\Fields\Relations\HasManyThrough;
use Livewire\Livewire;
use Lunar\Hub\Facades\Slot;
use Lunar\Models\Product;
use Lunar\Models\ProductVariant;

class LunarReviewsServiceProvider extends ServiceProvider
{
    protected $root = __DIR__.'/..';

    /**
     * Register the application services.
     */
    public function register(): void
    {
        $this->registerConfig();

        $this->loadTranslationsFrom(
            "{$this->root}/lang",
            'lunar-api-reviews',
        );

        $this->registerSchemas();

        $this->booting(function () {
            $this->registerPolicies();
        });

        $this->registerDynamicRelations();

        $this->extendSchemas();
    }

    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom("{$this->root}/database/migrations");
        $this->loadViewsFrom(__DIR__.'/Domain/Hub/resources/views', 'lunar-api-reviews');
        $this->loadRoutesFrom("{$this->root}/routes/api.php");

        // TODO: Add slots to Filament
        // Livewire::component(
        //     'lunar-api-reviews::reviews-slot',
        //     ReviewsSlot::class,
        // );
        //
        // Slot::register(
        //     'product.show',
        //     ReviewsSlot::class,
        // );

        Review::observe(ReviewObserver::class);

        if ($this->app->runningInConsole()) {
            $this->publishConfig();
            $this->publishTranslations();
            $this->publishViews();
        }
    }

    /**
     * Register config files.
     */
    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(
            "{$this->root}/config/reviews.php",
            'lunar-api.reviews',
        );
    }

    /**
     * Publish config files.
     */
    protected function publishConfig(): void
    {
        $this->publishes([
            "{$this->root}/config/reviews.php" => config_path('lunar-api.reviews.php'),
        ], 'lunar-api-reviews');
    }

    /**
     * Publish translations.
     */
    protected function publishTranslations(): void
    {
        $this->publishes([
            __DIR__.'/../lang' => $this->app->langPath('vendor/lunar-api'),
        ], 'lunar-api-reviews.translations');
    }

    /**
     * Register schemas.
     */
    public function registerSchemas(): void
    {
        SchemaManifestFacade::registerSchema(ReviewSchema::class);
    }

    /**
     * Publish views.
     */
    protected function publishViews(): void
    {
        $this->publishes([
            __DIR__.'/Domain/Hub/resources/views' => resource_path('views/vendor/lunar-api-reviews'),
        ], 'views');
    }

    /**
     * Register dynamic relations.
     */
    protected function registerDynamicRelations(): void
    {
        Product::resolveRelationUsing('variantReviews', function ($model) {
            return $model
                ->hasManyThrough(
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

    /**
     * Extend schemas.
     */
    protected function extendSchemas(): void
    {
        /** @var SchemaManifest $schemaManifest */
        $schemaManifest = $this->app->make(SchemaManifest::class);

        /** @var ResourceManifest $resourceManifest */
        $resourceManifest = $this->app->make(ResourceManifest::class);

        /** @var SchemaExtension $productSchemaExtenstion */
        $productSchemaExtenstion = $schemaManifest::extend(ProductSchema::class);

        $productSchemaExtenstion
            ->setWith([
                'reviews',
            ])
            ->setIncludePaths([
                'reviews',
                'reviews.user',
                'reviews.user.customers',
                'variants.reviews',
                'variants.reviews.user',
                'variants.reviews.user.customers',
            ])
            ->setFields([
                fn () => Number::make('rating', 'review_rating'),
                fn () => Number::make('review_count')
                    ->extractUsing(
                        static fn ($model) => $model->relationLoaded('reviews')
                            ? $model->reviews->count()
                            : $model->reviews()->count(),
                    ),
                fn () => HasManyThrough::make('reviews')->serializeUsing(
                    static fn ($relation) => $relation->withoutLinks(),
                ),
            ])
            ->setShowRelated([
                'reviews',
            ])
            ->setShowRelationship([
                'reviews',
            ]);

        /** @var ResourceExtension $productResourceExtension */
        $productResourceExtension = $resourceManifest::extend(ProductResource::class);

        $productResourceExtension
            ->setRelationships(fn ($resource) => [
                $resource->relation('reviews'),
            ]);

        /** @var SchemaExtension $productVariantSchemaExtenstion */
        $productVariantSchemaExtenstion = $schemaManifest::extend(ProductVariantSchema::class);

        $productVariantSchemaExtenstion
            ->setIncludePaths([
                'reviews',
                'reviews.user',
                'reviews.user.customers',
            ])
            ->setFields([
                fn () => HasMany::make('reviews')->serializeUsing(
                    static fn ($relation) => $relation->withoutLinks(),
                ),
            ])
            ->setShowRelated([
                'reviews',
            ])
            ->setShowRelationship([
                'reviews',
            ]);

        /** @var ResourceExtension $productVariantResourceExtension */
        $productVariantResourceExtension = $resourceManifest::extend(ProductVariantResource::class);

        $productVariantResourceExtension
            ->setRelationships(fn ($resource) => [
                'reviews' => $resource->relation('reviews'),
            ]);
    }

    /**
     * Register the application's policies.
     */
    public function registerPolicies(): void
    {
        DomainConfigCollection::fromConfig('lunar-api.reviews.domains')
            ->getPolicies()
            ->each(
                fn (string $policy, string $model) => Gate::policy($model, $policy),
            );
    }
}
