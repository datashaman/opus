<?php

namespace Database\Factories;

use App\Enums\ProvenanceAction;
use App\Enums\ProvenanceSource;
use App\Models\ProvenanceRecord;
use App\Models\Section;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProvenanceRecord>
 */
class ProvenanceRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'provenanceable_type' => Section::class,
            'provenanceable_id' => Section::factory(),
            'action' => ProvenanceAction::Created,
            'source' => ProvenanceSource::Agent,
            'source_name' => 'Composer',
            'reason' => fake()->optional()->sentence(),
            'from_state' => null,
        ];
    }
}
