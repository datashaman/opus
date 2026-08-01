<?php

namespace App\Models;

use App\Concerns\HasProvenanceRecords;
use App\Enums\LifecycleState;
use Database\Factories\MotifAppearanceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $motif_id
 * @property string $section_id
 * @property int $start_bar
 * @property int $end_bar
 * @property string|null $description
 * @property LifecycleState $lifecycle_state
 * @property int $version
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['motif_id', 'section_id', 'start_bar', 'end_bar', 'description', 'lifecycle_state', 'version'])]
class MotifAppearance extends Model
{
    /** @use HasFactory<MotifAppearanceFactory> */
    use HasFactory, HasProvenanceRecords, HasUlids;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'lifecycle_state' => LifecycleState::class,
        ];
    }

    /**
     * @return BelongsTo<Motif, $this>
     */
    public function motif(): BelongsTo
    {
        return $this->belongsTo(Motif::class);
    }

    /**
     * @return BelongsTo<Section, $this>
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * @return HasMany<Transformation, $this>
     */
    public function transformations(): HasMany
    {
        return $this->hasMany(Transformation::class);
    }
}
