<?php

namespace Database\Factories;

use App\Models\Feedback;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Feedback>
 */
class FeedbackFactory extends Factory
{
    protected $model = Feedback::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => $this->faker->boolean(80) ? User::factory() : null, // 80% chance of having user_id
            'rating' => $this->faker->numberBetween(1, 5),
            'komentar' => $this->faker->paragraph(3),
            'balasan' => $this->faker->boolean(30) ? $this->faker->paragraph(2) : null, // 30% chance of having reply
        ];
    }

    /**
     * Indicate that the feedback has a reply.
     */
    public function withReply(): static
    {
        return $this->state(fn (array $attributes) => [
            'balasan' => $this->faker->paragraph(2),
        ]);
    }

    /**
     * Indicate that the feedback is anonymous.
     */
    public function anonymous(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => null,
        ]);
    }

    /**
     * Indicate that the feedback has a specific rating.
     */
    public function rating(int $rating): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => $rating,
        ]);
    }
}
