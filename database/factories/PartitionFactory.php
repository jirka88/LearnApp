<?php

namespace Database\Factories;

use App\Models\Partition;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PartitionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Partition::class;
    public function definition()
    {
        return [
            'name' => fake()->firstName(),
            'icon' => fake()->firstName(),
        ];
    }
}
