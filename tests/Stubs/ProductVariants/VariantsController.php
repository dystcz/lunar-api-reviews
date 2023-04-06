<?php

namespace Dystcz\LunarApiReviews\Tests\Stubs\ProductVariants;

use Dystcz\LunarApiReviews\Domain\Base\Http\Controllers\Controller;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchRelated;

class VariantsController extends Controller
{
    use FetchRelated;
}
