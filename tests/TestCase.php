<?php

namespace Dystcz\LunarApiReviews\Tests;

use Dystcz\LunarApi\Tests\Stubs\Lunar\TestUrlGenerator;
use Dystcz\LunarApiReviews\LunarReviewsServiceProvider;
use Dystcz\LunarApiReviews\Tests\Stubs\JsonApi\Server;
use Dystcz\LunarApiReviews\Tests\Stubs\ProductVariants\ProductVariantRouteGroup;
use Dystcz\LunarApiReviews\Tests\Stubs\Users\User;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Config;
use LaravelJsonApi\Testing\MakesJsonApiRequests;
use LaravelJsonApi\Testing\TestExceptionHandler;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    use MakesJsonApiRequests;

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('auth.providers.users.model', User::class);

        Config::set('lunar.urls.generator', TestUrlGenerator::class);

        activity()->disableLogging();
    }

    /**
     * @param  Application  $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        Config::set('lunar-api.additional_servers', [Server::class]);

        return [
            // Lunar Reviews
            LunarReviewsServiceProvider::class,

            // Laravel JsonApi
            \LaravelJsonApi\Encoder\Neomerx\ServiceProvider::class,
            \LaravelJsonApi\Laravel\ServiceProvider::class,
            \LaravelJsonApi\Spec\ServiceProvider::class,

            // Lunar Api
            \Dystcz\LunarApi\LunarApiServiceProvider::class,

            // Livewire
            \Lunar\LivewireTables\LivewireTablesServiceProvider::class,
            \Livewire\LivewireServiceProvider::class,

            // Lunar Hub
            \Lunar\Hub\AdminHubServiceProvider::class,

            // Lunar core
            \Lunar\LunarServiceProvider::class,
            \Spatie\MediaLibrary\MediaLibraryServiceProvider::class,
            \Spatie\Activitylog\ActivitylogServiceProvider::class,
            \Cartalyst\Converter\Laravel\ConverterServiceProvider::class,
            \Kalnoy\Nestedset\NestedSetServiceProvider::class,
            \Spatie\LaravelBlink\BlinkServiceProvider::class,
        ];
    }

    /**
     * @param  Application  $app
     */
    public function getEnvironmentSetUp($app)
    {
        Config::set('database.default', 'sqlite');

        Config::set('database.migrations', 'migrations');

        Config::set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function resolveApplicationExceptionHandler($app): void
    {
        $app->singleton(
            ExceptionHandler::class,
            TestExceptionHandler::class
        );
    }

    /**
     * Define database migrations.
     */
    protected function defineDatabaseMigrations(): void
    {
        $this->loadLaravelMigrations();
    }

    /**
     * Define routes setup.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    protected function defineRoutes($router)
    {
        (new ProductVariantRouteGroup)();
    }
}
