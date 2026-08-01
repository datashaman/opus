<?php

namespace Database\Factories;

use App\Enums\LifecycleState;
use App\Enums\TransformationType;
use App\Models\MotifAppearance;
use App\Models\Transformation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Transformation>
 */
class TransformationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'motif_appearance_id' => MotifAppearance::factory(),
            'type' => fake()->randomElement(TransformationType::cases()),
            'parameters' => ['interval' => fake()->numberBetween(-12, 12)],
            'description' => fake()->optional()->sentence(),
            'lifecycle_state' => LifecycleState::Draft,
            'version' => 1,
        ];
    }
}
