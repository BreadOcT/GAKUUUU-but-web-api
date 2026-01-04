<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tugas>
 */
class TugasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'judul' => 'Tugas ' . $this->faker->words(2, true),
            'jenis' => $this->faker->randomElement(['kuis', 'tugas_harian', 'ujian']),
            'deskripsi' => $this->faker->paragraph(),
            'deadline' => $this->faker->dateTimeBetween('now', '+1 month'),
        ];
    }
}
