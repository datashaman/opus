<?php

use App\Enums\ConstraintScope;
use App\Enums\LifecycleState;
use App\Enums\ProvenanceAction;
use App\Enums\ProvenanceSource;
use App\Models\GraphConstraint;
use App\Models\Motif;
use App\Models\MotifAppearance;
use App\Models\Project;
use App\Models\ProvenanceRecord;
use App\Models\Section;
use App\Models\Transformation;
use Illuminate\Support\Str;

test('project has a ulid primary key and can be created via factory', function () {
    $project = Project::factory()->create();

    expect(Str::isUlid($project->id))->toBeTrue()
        ->and($project->lifecycle_state)->toBe(LifecycleState::Draft);
});

test('sections belong to a project and support self-referential hierarchy', function () {
    $project = Project::factory()->create();
    $parent = Section::factory()->for($project)->create();
    $child = Section::factory()->for($project)->create(['parent_section_id' => $parent->id]);

    expect($child->project->is($project))->toBeTrue()
        ->and($child->parentSection->is($parent))->toBeTrue()
        ->and($parent->childSections->pluck('id'))->toContain($child->id);
});

test('motif appearances link a motif to a section and expose transformations', function () {
    $appearance = MotifAppearance::factory()->create();
    $transformation = Transformation::factory()->for($appearance, 'motifAppearance')->create();

    expect($appearance->motif)->toBeInstanceOf(Motif::class)
        ->and($appearance->section)->toBeInstanceOf(Section::class)
        ->and($appearance->transformations->pluck('id'))->toContain($transformation->id)
        ->and($transformation->motifAppearance->is($appearance))->toBeTrue();
});

test('constraints scope to a project and support a polymorphic constrainable target', function () {
    $project = Project::factory()->create();
    $section = Section::factory()->for($project)->create();

    $constraint = GraphConstraint::factory()->for($project)->create([
        'scope' => ConstraintScope::Section,
        'constrainable_type' => Section::class,
        'constrainable_id' => $section->id,
    ]);

    expect($constraint->project->is($project))->toBeTrue()
        ->and($constraint->constrainable->is($section))->toBeTrue();
});

test('provenance records are append-only and attach to any graph entity via morph', function () {
    $section = Section::factory()->create();

    $record = ProvenanceRecord::factory()->create([
        'provenanceable_type' => Section::class,
        'provenanceable_id' => $section->id,
        'action' => ProvenanceAction::Created,
        'source' => ProvenanceSource::Agent,
    ]);

    expect($record->provenanceable->is($section))->toBeTrue()
        ->and($section->provenanceRecords->pluck('id'))->toContain($record->id)
        ->and($record->updated_at)->toBeNull();
});
