<?php

namespace Dystcz\LunarReviews\Tests;

use Cartalyst\Converter\Laravel\ConverterServiceProvider;
use Dystcz\LunarReviews\LunarReviewsServiceProvider;
use Kalnoy\Nestedset\NestedSetServiceProvider;
use LaravelJsonApi\Encoder\Neomerx\ServiceProvider;
use LaravelJsonApi\Laravel\ServiceProvider as LaravelJsonApiServiceProvider;
use LaravelJsonApi\Testing\MakesJsonApiRequests;
use Lunar\Database\Factories\LanguageFactory;
use Lunar\Hub\Tests\Stubs\User;
use Lunar\LunarServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\Activitylog\ActivitylogServiceProvider;
use Spatie\LaravelBlink\BlinkServiceProvider;
use Spatie\MediaLibrary\MediaLibraryServiceProvider;

abstract class TestCase extends Orchestra
{
    use MakesJsonApiRequests;

    protected function setUp(): void
    {
        parent::setUp();

        LanguageFactory::new()->create([
            'code' => 'en',
            'name' => 'English',
        ]);

        config()->set('providers.users.model', User::class);

        activity()->disableLogging();
    }

    /**
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            LunarReviewsServiceProvider::class,
            ServiceProvider::class,
            LaravelJsonApiServiceProvider::class,

            LunarServiceProvider::class,
            MediaLibraryServiceProvider::class,
            ActivitylogServiceProvider::class,
            ConverterServiceProvider::class,
            NestedSetServiceProvider::class,
            BlinkServiceProvider::class,
        ];
    }

    /**
     * @param  \Illuminate\Foundation\Application  $app
     */
    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'sqlite');

        config()->set('database.migrations', 'migrations');

        config()->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }
}
