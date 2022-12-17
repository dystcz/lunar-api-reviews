<?php

namespace Dystcz\LunarReviews\Domain\Reviews\Http\Controllers;

use Dystcz\LunarReviews\Controller;
use LaravelJsonApi\Laravel\Http\Controllers\Actions;

class ReviewsController extends Controller
{
    use Actions\FetchMany;
    use Actions\FetchOne;
    use Actions\Store;
    use Actions\Destroy;
}
