<?php

namespace App\Models;

use App\Enums\ProvenanceAction;
use App\Enums\ProvenanceSource;
use Database\Factories\ProvenanceRecordFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $provenanceable_type
 * @property string $provenanceable_id
 * @property ProvenanceAction $action
 * @property ProvenanceSource $source
 * @property string|null $source_name
 * @property string|null $reason
 * @property array<string, mixed>|null $from_state
 * @property Carbon|null $created_at
 */
#[Fillable(['provenanceable_type', 'provenanceable_id', 'action', 'source', 'source_name', 'reason', 'from_state'])]
class ProvenanceRecord extends Model
{
    /** @use HasFactory<ProvenanceRecordFactory> */
    use HasFactory, HasUlids;

    /**
     * Provenance records are append-only; there is no updated_at column.
     */
    const UPDATED_AT = null;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'action' => ProvenanceAction::class,
            'source' => ProvenanceSource::class,
            'from_state' => 'array',
        ];
    }

    /**
     * @return MorphTo<Model, $this>
     */
    public function provenanceable(): MorphTo
    {
        return $this->morphTo();
    }
}
