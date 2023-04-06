<?php

namespace Dystcz\LunarApiReviews\Tests\Stubs\JsonApi;

use Dystcz\LunarApiReviews\Domain\JsonApi\V1\Server as BaseServer;
use Dystcz\LunarApiReviews\Domain\Reviews\JsonApi\V1\ReviewSchema;
use Dystcz\LunarApiReviews\Tests\Stubs\ProductVariants\ProductVariantSchema;
use Dystcz\LunarApiReviews\Tests\Stubs\Users\UserSchema;

class Server extends BaseServer
{
    /**
     * Get the server's list of schemas.
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
