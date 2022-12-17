<?php

namespace Dystcz\LunarReviews\Tests\Stubs\ProductVariants;

use Dystcz\LunarReviews\Controller;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\AttachRelationship;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchMany;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchOne;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchRelated;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchRelationship;

class VariantsController extends Controller
{
    use FetchMany;
    use FetchOne;
    use FetchRelated;
    use FetchRelationship;
    use AttachRelationship;
}
