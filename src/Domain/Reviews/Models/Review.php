<?php

namespace Dystcz\LunarReviews\Domain\Reviews\Models;

use Auth;
use Illuminate\Database\Eloquent\Builder;
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
        static::creating(static function (Review $review): void {
            $review->user_id = $review->user_id ?: Auth::user()?->id;
        });
    }

    public function purchasable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            Config::get('auth.providers.users.model')
        );
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at');
    }
}
