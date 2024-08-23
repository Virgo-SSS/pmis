<?php

namespace Database\Factories;

use App\Models\Bank;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserBank>
 */
class UserBankFactory extends Factory
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
            'bank_id' => $this->getBankId(),
            'account_number' => $this->faker->word,
            'account_name' => $this->faker->word,
        ];
    }

    private function getBankId(): int
    {
        $bank = Bank::query()->inRandomOrder()->first();

        return $bank->id ?? Bank::factory()->create()->id;
    }
}
