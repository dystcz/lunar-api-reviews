<?php

namespace Dystcz\LunarReviews\Domain\ProductVariants\Http\Controllers;

use Dystcz\LunarApi\Controller;
use LaravelJsonApi\Laravel\Http\Controllers\Actions;

class ProductVariantsController extends Controller
{
    use Actions\FetchRelated;
    use Actions\FetchRelationship;
}
