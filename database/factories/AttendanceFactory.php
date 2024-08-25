<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendance>
 */
class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => $this->getUserId(),
            'clock_in' => $this->faker->dateTimeThisYear()->format('Y-m-d H:i:s'),
            'clock_out' => $this->faker->dateTimeThisYear()->format('Y-m-d H:i:s'),
            'is_late' => $this->faker->boolean,
            'status' => $this->faker->randomElement([0, 1, 2]),
            'overtime' => '0' . $this->faker->numberBetween(0, 3) . ':00:00',
            'note' => $this->faker->sentence,
        ];
    }

    /**
     * Get a random user id
     *
     * @return int
     */
    private function getUserId(): int
    {
        return User::query()->inRandomOrder()->first()->id ?? User::factory()->create()->id;
    }
}
