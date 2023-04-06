<?php

namespace Dystcz\LunarApiReviews\Domain\Reviews\Http\Controllers;

use Dystcz\LunarApiReviews\Domain\Base\Http\Controllers\Controller;
use LaravelJsonApi\Laravel\Http\Controllers\Actions;

class ReviewsController extends Controller
{
    use Actions\FetchMany;
    use Actions\FetchOne;
    use Actions\Store;
    use Actions\Destroy;

    public function __construct()
    {
        $this->middleware(Config::get('lunar-reviews.auth_middleware'))->only('store');
    }
}
