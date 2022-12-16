<?php

namespace Dystcz\LunarReviews\Domain\Reviews\Models;

use Lunar\Base\BaseModel;

class Review extends BaseModel
{
    public function purchasable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }
}
