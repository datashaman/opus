<?php

namespace App\Models;

use App\Concerns\HasProvenanceRecords;
use App\Enums\ConstraintScope;
use App\Enums\LifecycleState;
use Database\Factories\GraphConstraintFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $project_id
 * @property ConstraintScope $scope
 * @property string|null $constrainable_type
 * @property string|null $constrainable_id
 * @property string $name
 * @property string|null $description
 * @property array<string, mixed>|null $parameters
 * @property LifecycleState $lifecycle_state
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['project_id', 'scope', 'constrainable_type', 'constrainable_id', 'name', 'description', 'parameters', 'lifecycle_state'])]
class GraphConstraint extends Model
{
    /** @use HasFactory<GraphConstraintFactory> */
    use HasFactory, HasProvenanceRecords, HasUlids;

    protected $table = 'constraints';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'scope' => ConstraintScope::class,
            'parameters' => 'array',
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
     * @return MorphTo<Model, $this>
     */
    public function constrainable(): MorphTo
    {
        return $this->morphTo();
    }
}
