<?php

namespace App\Mcp\Resources;

use App\Models\Motif;
use App\Models\MotifAppearance;
use App\Models\Transformation;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\MimeType;
use Laravel\Mcp\Server\Contracts\HasUriTemplate;
use Laravel\Mcp\Server\Resource;
use Laravel\Mcp\Support\UriTemplate;

#[Description('Motif catalog, appearances, and transformations for a project.')]
#[MimeType('application/json')]
class MotifsResource extends Resource implements HasUriTemplate
{
    public function uriTemplate(): UriTemplate
    {
        return new UriTemplate('composition://project/{projectId}/motifs');
    }

    /**
     * Handle the resource request.
     */
    public function handle(Request $request): Response
    {
        $projectId = $request->get('projectId');

        $motifs = Motif::query()
            ->where('project_id', $projectId)
            ->with('appearances.transformations')
            ->get();

        return Response::text(json_encode($motifs->map(fn (Motif $motif) => [
            'id' => $motif->id,
            'name' => $motif->name,
            'type' => $motif->type->value,
            'description' => $motif->description,
            'lifecycle_state' => $motif->lifecycle_state->value,
            'version' => $motif->version,
            'appearances' => $motif->appearances->map(fn (MotifAppearance $appearance) => [
                'id' => $appearance->id,
                'section_id' => $appearance->section_id,
                'start_bar' => $appearance->start_bar,
                'end_bar' => $appearance->end_bar,
                'description' => $appearance->description,
                'transformations' => $appearance->transformations->map(fn (Transformation $transformation) => [
                    'id' => $transformation->id,
                    'type' => $transformation->type->value,
                    'parameters' => $transformation->parameters,
                    'description' => $transformation->description,
                ])->all(),
            ])->all(),
        ])->all(), JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR));
    }
}
