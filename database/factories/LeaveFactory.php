<?php

namespace Database\Factories;

use App\Enums\LeaveStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Leave>
 */
class LeaveFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => $this->userId(),
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
            'reason' => $this->faker->sentence,
        ];
    }

    /**
     * Indicate that the leave is approved.
     *
     * @return \Database\Factories\LeaveFactory
     */
    public function approved(): LeaveFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => LeaveStatus::APPROVED,
            ];
        });
    }

    /**
     * Indicate that the leave is rejected.
     *
     * @return \Database\Factories\LeaveFactory
     */
    public function rejected(): LeaveFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => LeaveStatus::REJECTED,
            ];
        });
    }

    /**
     * Get Random user id
     *
     * @return int
     */
    public function userId(): int
    {
        return User::query()->inRandomOrder()->first()->id ?? User::factory()->create()->id;
    }
}
