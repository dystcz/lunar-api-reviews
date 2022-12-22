<?php

namespace Dystcz\LunarReviews\Domain\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Servers\Server as BaseServer;
use Dystcz\LunarReviews\Domain\Reviews\JsonApi\V1\ReviewSchema;
use Illuminate\Support\Facades\Config;

class Server extends BaseServer
{
    /**
     * Set base server URI.
     *
     * @param string $path
     * @return void
     */
    protected function setBaseUri(string $path = 'v1'): void
    {
        $prefix = Config::get('lunar-api.route_prefix');

        $this->baseUri = "/{$prefix}/{$path}";
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
