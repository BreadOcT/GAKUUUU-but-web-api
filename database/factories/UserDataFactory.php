<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserData>
 */
class UserDataFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'no_telepon' => $this->faker->phoneNumber(),
            'alamat_lengkap' => $this->faker->address(),
            'kota' => $this->faker->city(),
            'negara' => 'Indonesia',
        ];
    }
}
