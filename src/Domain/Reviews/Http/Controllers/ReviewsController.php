<?php

namespace Dystcz\LunarApiReviews\Domain\Reviews\Http\Controllers;

use Dystcz\LunarApi\Base\Controller;
use Illuminate\Support\Facades\Config;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\Destroy;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchMany;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchOne;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\Store;

class ReviewsController extends Controller
{
    use Destroy;
    use FetchMany;
    use FetchOne;
    use Store;

    public function __construct()
    {
        if (Config::get('lunar-api.reviews.domains.reviews.settings.auth_required', true)) {
            $this
                ->middleware(Config::get(
                    'lunar-api.reviews.domains.reviews.settings.auth_middleware',
                    ['auth'],
                ))
                ->only('store');
        }
    }
}
