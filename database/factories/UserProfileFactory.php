<?php

namespace Database\Factories;

use App\Services\FileService;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\Testing\File;

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
            'gender' => $this->faker->randomElement(['F', 'M']),
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

    /**
     * Indicate user has a profile picture.
     *
     * @param string|File $profilePicture
     * @param bool $isNeedStoreFile
     * @return UserProfileFactory
     */
    public function profilePicture(string|File $profilePicture, bool $isNeedStoreFile = false): UserProfileFactory
    {
        if(!$isNeedStoreFile) {
            return $this->state(fn (array $attributes) => [
                'profile_picture' => $profilePicture,
            ]);
        }

        $filename = $profilePicture;
        if(is_file($profilePicture)) {
            $filename = app(FileService::class)->storeFile($profilePicture, 'user-profile-pictures', isPublic: true);
        }

        return $this->state(fn (array $attributes) => [
            'profile_picture' => $filename,
        ]);
    }
}
