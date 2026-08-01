<?php

namespace App\Mcp\Resources;

use App\Models\GraphConstraint;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\MimeType;
use Laravel\Mcp\Server\Contracts\HasUriTemplate;
use Laravel\Mcp\Server\Resource;
use Laravel\Mcp\Support\UriTemplate;

#[Description('Active constraints and their scopes for a project.')]
#[MimeType('application/json')]
class ConstraintsResource extends Resource implements HasUriTemplate
{
    public function uriTemplate(): UriTemplate
    {
        return new UriTemplate('composition://project/{projectId}/constraints');
    }

    /**
     * Handle the resource request.
     */
    public function handle(Request $request): Response
    {
        $projectId = $request->get('projectId');

        $constraints = GraphConstraint::query()
            ->where('project_id', $projectId)
            ->get();

        return Response::text(json_encode($constraints->map(fn (GraphConstraint $constraint) => [
            'id' => $constraint->id,
            'scope' => $constraint->scope->value,
            'constrainable_type' => $constraint->constrainable_type,
            'constrainable_id' => $constraint->constrainable_id,
            'name' => $constraint->name,
            'description' => $constraint->description,
            'parameters' => $constraint->parameters,
            'lifecycle_state' => $constraint->lifecycle_state->value,
        ])->all(), JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR));
    }
}
