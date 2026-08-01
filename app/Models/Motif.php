<?php

namespace App\Models;

use App\Concerns\HasProvenanceRecords;
use App\Enums\LifecycleState;
use App\Enums\MotifType;
use Database\Factories\MotifFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $project_id
 * @property string $name
 * @property MotifType $type
 * @property string|null $description
 * @property LifecycleState $lifecycle_state
 * @property int $version
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['project_id', 'name', 'type', 'description', 'lifecycle_state', 'version'])]
class Motif extends Model
{
    /** @use HasFactory<MotifFactory> */
    use HasFactory, HasProvenanceRecords, HasUlids;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => MotifType::class,
            'lifecycle_state' => LifecycleState::class,
        ];
    }

    /**
     * @return BelongsTo<Project, $this>
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * @return HasMany<MotifAppearance, $this>
     */
    public function appearances(): HasMany
    {
        return $this->hasMany(MotifAppearance::class);
    }
}
