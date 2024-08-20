<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserProfile>
 */
class UserProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => 1,
            'phone' => $this->faker->phoneNumber,
            'emergency_contact' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'joined_at' => $this->faker->date(),
        ];
    }

    /**
     * Indicate that the user profile belongs to the department.
     *
     * @param int $departmentId
     * @return UserProfileFactory
     */
    public function department(int $departmentId): UserProfileFactory
    {
        return $this->state(fn (array $attributes) => [
            'department_id' => $departmentId,
        ]);
    }
}
