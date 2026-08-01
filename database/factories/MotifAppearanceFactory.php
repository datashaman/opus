<?php

namespace Database\Factories;

use App\Enums\LifecycleState;
use App\Models\Motif;
use App\Models\MotifAppearance;
use App\Models\Project;
use App\Models\Section;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MotifAppearance>
 */
class MotifAppearanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $project = Project::factory()->create();
        $startBar = fake()->numberBetween(1, 100);

        return [
            'motif_id' => Motif::factory()->for($project, 'project'),
            'section_id' => Section::factory()->for($project, 'project'),
            'start_bar' => $startBar,
            'end_bar' => $startBar + fake()->numberBetween(1, 8),
            'description' => fake()->optional()->sentence(),
            'lifecycle_state' => LifecycleState::Draft,
            'version' => 1,
        ];
    }
}
