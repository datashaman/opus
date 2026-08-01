<?php

namespace App\Mcp\Servers;

use App\Mcp\Resources\ConstraintsResource;
use App\Mcp\Resources\MotifsResource;
use App\Mcp\Resources\ProvenanceResource;
use App\Mcp\Resources\SectionsResource;
use Laravel\Mcp\Server;
use Laravel\Mcp\Server\Attributes\Instructions;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Attributes\Version;

#[Name('Composition Server')]
#[Version('0.1.0')]
#[Instructions('Read-only access to the Opus MCP composition graph: sections, motifs, appearances, transformations, constraints, and provenance for a project.')]
class CompositionServer extends Server
{
    protected array $tools = [
        //
    ];

    /**
     * @var array<int, class-string<Server\Resource>>
     */
    protected array $resources = [
        SectionsResource::class,
        MotifsResource::class,
        ConstraintsResource::class,
        ProvenanceResource::class,
    ];

    protected array $prompts = [
        //
    ];
}
