<?php

namespace Database\Factories;

use App\Enums\ConstraintScope;
use App\Enums\LifecycleState;
use App\Models\GraphConstraint;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GraphConstraint>
 */
class GraphConstraintFactory extends Factory
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
            'scope' => ConstraintScope::Global,
            'constrainable_type' => null,
            'constrainable_id' => null,
            'name' => fake()->words(3, true),
            'description' => fake()->optional()->sentence(),
            'parameters' => ['max_density' => fake()->numberBetween(1, 10)],
            'lifecycle_state' => LifecycleState::Active,
        ];
    }
}
