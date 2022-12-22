<?php

namespace Dystcz\LunarReviews\Domain\Reviews\Models;

use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Config;
use Lunar\Base\BaseModel;

class Review extends BaseModel
{
    protected $fillable = [
        'rating',
        'comment',
        'published_at',
        'user_id',
        'purchasable_id',
        'purchasable_type',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(static function (self $review): void {
            $review->user_id = $review->user_id ?: Auth::user()?->id;
        });
    }

    /**
     * Purchasable relation.
     *
     * @return MorphTo
     */
    public function purchasable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * User relation.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(
            Config::get('auth.providers.users.model')
        );
    }

    /**
     * Scope published reviews.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at');
    }
}
