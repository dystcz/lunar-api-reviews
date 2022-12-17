<?php

namespace Dystcz\LunarReviews\Domain\Reviews\JsonApi\V1;

use Illuminate\Validation\Rule;
use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;
use LaravelJsonApi\Validation\Rule as JsonApiRule;
use Lunar\Models\ProductVariant;

class ReviewRequest extends ResourceRequest
{
    /**
     * Get the validation rules for the resource.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'rating' => ['required', 'integer'],
            'comment' => ['nullable', 'string'],
            'purchasable_id' => ['required', 'integer'],
            'purchasable_type' => ['required', 'string'],
            'published_at' => ['nullable', JsonApiRule::dateTime()],
        ];
    }
}
