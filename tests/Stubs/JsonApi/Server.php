<?php

namespace Dystcz\LunarReviews\Tests\Stubs\JsonApi;

use Dystcz\LunarReviews\Domain\JsonApi\V1\Server as BaseServer;
use Dystcz\LunarReviews\Domain\Reviews\JsonApi\V1\ReviewSchema;
use Dystcz\LunarReviews\Tests\Stubs\ProductVariants\ProductVariantSchema;
use Dystcz\LunarReviews\Tests\Stubs\Users\UserSchema;

class Server extends BaseServer
{
    /**
     * Get the server's list of schemas.
     *
     * @return array
     */
    protected function allSchemas(): array
    {
        return [
            UserSchema::class,
            ReviewSchema::class,
            ProductVariantSchema::class,
        ];
    }
}
