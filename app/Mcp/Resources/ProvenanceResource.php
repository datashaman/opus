<?php

namespace App\Mcp\Resources;

use App\Models\ProvenanceRecord;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\MimeType;
use Laravel\Mcp\Server\Contracts\HasUriTemplate;
use Laravel\Mcp\Server\Resource;
use Laravel\Mcp\Support\UriTemplate;

#[Description('Origin and change history for a graph entity.')]
#[MimeType('application/json')]
class ProvenanceResource extends Resource implements HasUriTemplate
{
    public function uriTemplate(): UriTemplate
    {
        return new UriTemplate('composition://project/{projectId}/provenance/{entityId}');
    }

    /**
     * Handle the resource request.
     */
    public function handle(Request $request): Response
    {
        $entityId = $request->get('entityId');

        $records = ProvenanceRecord::query()
            ->where('provenanceable_id', $entityId)
            ->orderByDesc('created_at')
            ->get();

        return Response::text(json_encode([
            'entity_id' => $entityId,
            'records' => $records->map(fn (ProvenanceRecord $record) => [
                'id' => $record->id,
                'entity_type' => $record->provenanceable_type,
                'action' => $record->action->value,
                'source' => $record->source->value,
                'source_name' => $record->source_name,
                'reason' => $record->reason,
                'from_state' => $record->from_state,
                'created_at' => $record->created_at?->toIso8601String(),
            ])->all(),
        ], JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR));
    }
}
