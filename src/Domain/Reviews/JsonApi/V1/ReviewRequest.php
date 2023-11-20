<?php

namespace Dystcz\LunarApiReviews\Domain\Reviews\JsonApi\V1;

use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;
use LaravelJsonApi\Validation\Rule as JsonApiRule;

class ReviewRequest extends ResourceRequest
{
    /**
     * Get the validation rules for the resource.
     *
     * @return array<string,array<int,mixed>>
     */
    public function rules(): array
    {
        return [
            'rating' => [
                'required',
                'integer',
                'min:1',
                'max:5',
            ],
            'comment' => [
                'nullable',
                'string',
            ],
            'purchasable_id' => [
                'required',
                'integer',
            ],
            'purchasable_type' => [
                'required',
                'string',
            ],
            'published_at' => [
                'nullable',
                JsonApiRule::dateTime(),
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string,string>
     */
    public function messages(): array
    {
        return [
            'rating.required' => __('lunar-api-reviews::validations.store_product_notification.rating.required'),
            'rating.integer' => __('lunar-api-reviews::validations.store_product_notification.rating.integer'),
            'rating.min' => __('lunar-api-reviews::validations.store_product_notification.rating.min'),
            'rating.max' => __('lunar-api-reviews::validations.store_product_notification.rating.max'),
            'comment.string' => __('lunar-api-reviews::validations.store_product_notification.comment.string'),
            'purchasable_id.required' => __('lunar-api-reviews::validations.store_product_notification.purchasable_id.required'),
            'purchasable_id.integer' => __('lunar-api-reviews::validations.store_product_notification.purchasable_id.integer'),
            'purchasable_type.required' => __('lunar-api-reviews::validations.store_product_notification.purchasable_type.required'),
            'purchasable_type.string' => __('lunar-api-reviews::validations.store_product_notification.purchasable_type.string'),
        ];
    }
}
