<?php

use App\Enums\ProvenanceAction;
use App\Enums\ProvenanceSource;
use App\Mcp\Resources\ConstraintsResource;
use App\Mcp\Resources\MotifsResource;
use App\Mcp\Resources\ProvenanceResource;
use App\Mcp\Resources\SectionsResource;
use App\Mcp\Servers\CompositionServer;
use App\Models\GraphConstraint;
use App\Models\MotifAppearance;
use App\Models\Project;
use App\Models\ProvenanceRecord;
use App\Models\Section;
use App\Models\Transformation;

test('sections resource returns sections scoped to the requested project', function () {
    $project = Project::factory()->create();
    $section = Section::factory()->for($project)->create(['name' => 'Chorus']);
    $otherProjectSection = Section::factory()->create(['name' => 'Other Project Verse']);

    $response = CompositionServer::resource(SectionsResource::class, ['projectId' => $project->id]);

    $response->assertOk()
        ->assertSee($section->id)
        ->assertSee('Chorus')
        ->assertDontSee($otherProjectSection->id);
});

test('motifs resource nests appearances and transformations', function () {
    $appearance = MotifAppearance::factory()->create();
    $transformation = Transformation::factory()->for($appearance, 'motifAppearance')->create();
    $motif = $appearance->motif;

    $response = CompositionServer::resource(MotifsResource::class, ['projectId' => $motif->project_id]);

    $response->assertOk()
        ->assertSee($motif->id)
        ->assertSee($appearance->id)
        ->assertSee($transformation->id);
});

test('constraints resource returns constraints for the requested project', function () {
    $project = Project::factory()->create();
    $constraint = GraphConstraint::factory()->for($project)->create(['name' => 'No density increase']);

    $response = CompositionServer::resource(ConstraintsResource::class, ['projectId' => $project->id]);

    $response->assertOk()
        ->assertSee($constraint->id)
        ->assertSee('No density increase');
});

test('provenance resource returns the change history for an entity', function () {
    $section = Section::factory()->create();
    $record = ProvenanceRecord::factory()->create([
        'provenanceable_type' => Section::class,
        'provenanceable_id' => $section->id,
        'action' => ProvenanceAction::Created,
        'source' => ProvenanceSource::Agent,
        'source_name' => 'Composer',
    ]);

    $response = CompositionServer::resource(ProvenanceResource::class, [
        'projectId' => $section->project_id,
        'entityId' => $section->id,
    ]);

    $response->assertOk()
        ->assertSee($record->id)
        ->assertSee('Composer');
});
