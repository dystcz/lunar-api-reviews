<?php

namespace Dystcz\LunarReviews\Domain\Reviews\Factories;

use Dystcz\LunarReviews\Domain\Reviews\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\Factories\UserFactory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'user_id' => UserFactory::new(),
            'rating' => $this->faker->numberBetween(1, 5),
            'comment' => $this->faker->text(100),
        ];
    }
}
