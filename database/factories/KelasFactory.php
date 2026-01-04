<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kelas>
 */
class KelasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'nama_kelas' => 'Kelas ' . $this->faker->randomElement(['A', 'B', 'C']) . ' - ' . $this->faker->randomElement(['Pagi', 'Siang', 'Sore']),
            'deskripsi' => 'Kelas intensif untuk persiapan ujian.',
        ];
    }
}
