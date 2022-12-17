<?php

namespace Dystcz\LunarReviews\Domain\Reviews\Models;

use Dystcz\LunarReviews\Tests\Stubs\User;
use Lunar\Base\BaseModel;

class Review extends BaseModel
{
    protected $guarded = [];

    protected $dates = ['published_at'];

    public function purchasable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Return the user relationship.
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            config('auth.providers.users.model')
        );
    }
}
