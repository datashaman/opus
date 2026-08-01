<?php

namespace Database\Factories;

use App\Enums\LifecycleState;
use App\Enums\MotifType;
use App\Models\Motif;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Motif>
 */
class MotifFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'name' => fake()->words(2, true),
            'type' => fake()->randomElement(MotifType::cases()),
            'description' => fake()->optional()->sentence(),
            'lifecycle_state' => LifecycleState::Draft,
            'version' => 1,
        ];
    }
}
