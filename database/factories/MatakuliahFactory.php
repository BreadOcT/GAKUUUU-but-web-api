<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Matakuliah>
 */
class MatakuliahFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $mapel = ['Matematika Dasar', 'Fisika Kuantum', 'Biologi Sel', 'Kimia Organik', 'Bahasa Inggris TOEFL', 'Algoritma Pemrograman', 'Sejarah Dunia'];
        return [
            'nama_mk' => $this->faker->randomElement($mapel) . ' ' . $this->faker->randomNumber(2),
            'deskripsi' => $this->faker->paragraph(),
            'manfaat' => $this->faker->sentence(),
            'tujuan' => $this->faker->sentence(),
        ];
    }
}
