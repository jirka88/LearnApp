<?php

namespace Database\Factories;

use App\Models\Chapter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Chapter>
 */
class ChapterFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Chapter::class;

    public function definition() {
        return [
            'name' => fake()->firstName(),
            'perex' => fake()->text(30),
            'context' => fake()->text(500),
        ];
    }
}
