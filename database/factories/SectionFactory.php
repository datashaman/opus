<?php

namespace Database\Factories;

use App\Enums\LifecycleState;
use App\Enums\NarrativeRole;
use App\Models\Project;
use App\Models\Section;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Section>
 */
class SectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startBar = fake()->numberBetween(1, 100);

        return [
            'project_id' => Project::factory(),
            'parent_section_id' => null,
            'name' => fake()->randomElement(['Verse', 'Chorus', 'Bridge', 'Intro', 'Outro']),
            'narrative_role' => fake()->randomElement(NarrativeRole::cases()),
            'start_bar' => $startBar,
            'end_bar' => $startBar + fake()->numberBetween(4, 32),
            'position' => fake()->numberBetween(0, 10),
            'lifecycle_state' => LifecycleState::Draft,
            'version' => 1,
        ];
    }
}
