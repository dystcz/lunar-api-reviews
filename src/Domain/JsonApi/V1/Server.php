<?php

namespace Dystcz\LunarReviews\Domain\JsonApi\V1;

use Dystcz\LunarReviews\Domain\Reviews\JsonApi\V1\ReviewSchema;
use Dystcz\LunarReviews\Domain\Reviews\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use LaravelJsonApi\Core\Server\Server as BaseServer;
use LaravelJsonApi\Core\Support\AppResolver;

class Server extends BaseServer
{
    /**
     * Server constructor.
     *
     * @param  AppResolver  $app
     * @param  string  $name
     */
    public function __construct(AppResolver $app, string $name)
    {
        $this->baseUri = '/'.Config::get('lunar-reviews.route_prefix').'/v1';

        parent::__construct($app, $name);
    }

    /**
     * Bootstrap the server when it is handling an HTTP request.
     *
     * @return void
     */
    public function serving(): void
    {
        Review::creating(static function (Review $review): void {
            $review->user()->associate(Auth::user());
        });
    }

    /**
     * Get the server's list of schemas.
     *
     * @return array
     */
    protected function allSchemas(): array
    {
        return [
            ReviewSchema::class,
        ];
    }
}
