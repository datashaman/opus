<?php

namespace App\Concerns;

use App\Models\ProvenanceRecord;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasProvenanceRecords
{
    /**
     * @return MorphMany<ProvenanceRecord, $this>
     */
    public function provenanceRecords(): MorphMany
    {
        return $this->morphMany(ProvenanceRecord::class, 'provenanceable');
    }
}
