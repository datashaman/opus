<?php

namespace App\Models;

use App\Concerns\HasProvenanceRecords;
use App\Enums\LifecycleState;
use App\Enums\TransformationType;
use Database\Factories\TransformationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $motif_appearance_id
 * @property TransformationType $type
 * @property array<string, mixed>|null $parameters
 * @property string|null $description
 * @property LifecycleState $lifecycle_state
 * @property int $version
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['motif_appearance_id', 'type', 'parameters', 'description', 'lifecycle_state', 'version'])]
class Transformation extends Model
{
    /** @use HasFactory<TransformationFactory> */
    use HasFactory, HasProvenanceRecords, HasUlids;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => TransformationType::class,
            'parameters' => 'array',
            'lifecycle_state' => LifecycleState::class,
        ];
    }

    /**
     * @return BelongsTo<MotifAppearance, $this>
     */
    public function motifAppearance(): BelongsTo
    {
        return $this->belongsTo(MotifAppearance::class);
    }
}
