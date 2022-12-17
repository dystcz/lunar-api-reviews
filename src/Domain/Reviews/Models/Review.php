<?php

namespace Dystcz\LunarReviews\Domain\Reviews\Models;

use Illuminate\Support\Facades\Config;
use Lunar\Base\BaseModel;

class Review extends BaseModel
{
    protected $fillable = [
        'rating',
        'comment',
        'published_at',
        'purchasable_id',
        'purchasable_type',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

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
            Config::get('auth.providers.users.model')
        );
    }
}
