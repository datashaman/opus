<?php

namespace App\Mcp\Resources;

use App\Models\Section;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\MimeType;
use Laravel\Mcp\Server\Contracts\HasUriTemplate;
use Laravel\Mcp\Server\Resource;
use Laravel\Mcp\Support\UriTemplate;

#[Description('Form, section hierarchy, boundaries, and narrative roles for a project.')]
#[MimeType('application/json')]
class SectionsResource extends Resource implements HasUriTemplate
{
    public function uriTemplate(): UriTemplate
    {
        return new UriTemplate('composition://project/{projectId}/sections');
    }

    /**
     * Handle the resource request.
     */
    public function handle(Request $request): Response
    {
        $projectId = $request->get('projectId');

        $sections = Section::query()
            ->where('project_id', $projectId)
            ->orderBy('start_bar')
            ->get();

        return Response::text(json_encode($sections->map(fn (Section $section) => [
            'id' => $section->id,
            'parent_section_id' => $section->parent_section_id,
            'name' => $section->name,
            'narrative_role' => $section->narrative_role?->value,
            'start_bar' => $section->start_bar,
            'end_bar' => $section->end_bar,
            'position' => $section->position,
            'lifecycle_state' => $section->lifecycle_state->value,
            'version' => $section->version,
        ])->all(), JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR));
    }
}
