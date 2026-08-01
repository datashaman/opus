<?php

namespace App\Models;

use App\Concerns\HasProvenanceRecords;
use App\Enums\LifecycleState;
use App\Enums\NarrativeRole;
use Database\Factories\SectionFactory;
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
 * @property string|null $parent_section_id
 * @property string $name
 * @property NarrativeRole|null $narrative_role
 * @property int $start_bar
 * @property int $end_bar
 * @property int $position
 * @property LifecycleState $lifecycle_state
 * @property int $version
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['project_id', 'parent_section_id', 'name', 'narrative_role', 'start_bar', 'end_bar', 'position', 'lifecycle_state', 'version'])]
class Section extends Model
{
    /** @use HasFactory<SectionFactory> */
    use HasFactory, HasProvenanceRecords, HasUlids;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'narrative_role' => NarrativeRole::class,
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
     * @return BelongsTo<Section, $this>
     */
    public function parentSection(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'parent_section_id');
    }

    /**
     * @return HasMany<Section, $this>
     */
    public function childSections(): HasMany
    {
        return $this->hasMany(Section::class, 'parent_section_id');
    }

    /**
     * @return HasMany<MotifAppearance, $this>
     */
    public function motifAppearances(): HasMany
    {
        return $this->hasMany(MotifAppearance::class);
    }
}
