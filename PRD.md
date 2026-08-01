# Opus MCP

## Design Brief

**Status:** Concept / architecture brief  
**Product type:** Headless composition engine with a read-only observability interface  
**Primary integration:** Ableton Live through an Ableton MCP adapter

---

## Vision

Opus MCP is a headless system for creating and evolving music at the level of ideas rather than individual notes. It gives AI agents a shared, structured model of a composition—its motifs, sections, harmony, performance intent, energy, and relationships—then translates accepted musical decisions into MIDI, automation, clips, scenes, and other operations that Ableton Live can render and play.

The system is not another digital audio workstation. Ableton remains the environment for sound selection, recording, detailed editing, mixing, performance, and final production. Opus MCP becomes the source of truth for musical meaning: what an idea is, why it exists, where it returns, how it changes, and which constraints shape it.

The long-term product is an IDE for musical ideas. A user can ask for a change such as “foreshadow the chorus in the second verse,” receive several explainable alternatives, audition them in Ableton, and accept, reject, merge, or branch the result without losing provenance.

## Core Principles

1. **Musical meaning is the primary abstraction.** Agents work with motifs, phrases, sections, harmonic functions, tension, density, orchestration, and performance character—not raw MIDI events unless rendering requires them.

2. **The composition graph is canonical.** Opus MCP owns the authoritative musical model. Ableton is a synchronized render and performance target, not the sole source of truth.

3. **Intent, performance, and render are separate layers.** A musical idea should survive changes in instrumentation, voicing, articulation, tempo, or production treatment.

4. **Every mutation is transactional.** Agent proposals are expressed as graph diffs that can be validated, previewed, applied atomically, reverted, compared, and branched.

5. **Provenance is part of the music.** Every generated or modified entity records who or what changed it, why, from which state, and under which constraints.

6. **Humans retain editorial authority.** Agents propose and explain. Users audition and decide. No destructive or ambiguous synchronization happens silently.

7. **The first UI is read-only.** It exists to make the system legible: state, relationships, decisions, alternatives, synchronization, and agent activity. Editing remains conversational, tool-driven, or inside Ableton.

8. **Round-trip ambiguity is explicit.** Manual Ableton changes are detected and classified rather than automatically overwriting semantic intent.

## Architecture

The system is composed of five cooperating layers:

### 1. Composition Core

Owns the canonical graph, stable entity identifiers, constraints, revisions, branches, validation rules, and transaction history. It exposes semantic state and mutations through MCP.

### 2. Agent Runtime

Coordinates specialist agents that inspect the graph and propose bounded changes. Agents operate against snapshots and return structured proposals rather than directly mutating Ableton or the canonical graph.

### 3. Render Planner

Compiles semantic intent and performance instructions into a deterministic render plan. The plan can include notes, velocities, timing, articulations, controller data, automation, clip boundaries, track assignments, and scene structure.

### 4. Ableton MCP Adapter

Translates render plans into Ableton operations, maintains stable mappings between graph entities and Ableton objects, reads relevant project state, and reports drift or conflicts back to the composition core.

### 5. Read-only UI

Projects synchronized views of the graph, timeline, alternatives, provenance, and integration health. It is an observability surface over the same MCP resources used by agents and adapters.

```text
User request
    ↓
Conductor → Specialist agents
    ↓             ↑
Proposed graph diffs
    ↓
Validation → Preview → Human decision
    ↓
Canonical composition graph
    ↓
Render planner → Ableton MCP adapter → Ableton Live
    ↑                                      ↓
    └──────── mappings, state, drift ──────┘
```

## Graph Model

The graph separates musical identity from its realization.

### Intent Layer

Describes what the composition means and how its ideas relate.

- Work, movement, and section
- Motif, theme, phrase, gesture, and rhythmic cell
- Harmonic region, progression, cadence, and tonal function
- Narrative role such as introduction, escalation, release, interruption, or return
- Energy, density, tension, salience, and contrast targets
- Relationships such as `introduces`, `develops`, `quotes`, `answers`, `contrasts_with`, `returns_in`, and `foreshadows`
- Musical and editorial constraints

### Performance Layer

Describes how an intent is realized by performers or instruments.

- Instrument and performer roles
- Register, range, voicing, doubling, and orchestration
- Articulation, dynamics, groove, timing feel, and expressive deviation
- Transformations such as inversion, transposition, augmentation, diminution, fragmentation, and reharmonization
- Entry and exit behavior
- Local performance constraints

### Render Layer

Contains generated or captured implementation details needed by a target environment.

- MIDI notes and controller events
- Automation envelopes
- Clip, scene, track, device, and locator references
- Audio or MIDI asset references
- Render hashes, timestamps, and target-specific metadata

Render data is reproducible output where possible. It must not erase the intent or performance representation from which it was derived.

### Cross-cutting Entities

Every graph entity has a stable ID, type, version, lifecycle state, provenance record, and optional target mappings. Edges are typed entities with their own provenance and confidence. Constraints can apply globally, to a branch, to a section, or to an individual entity.

### Revisions and Branches

Each accepted transaction creates a revision. Alternatives live on branches that share ancestry, enabling comparison at both semantic and rendered levels. A merge is a first-class musical operation: it may combine compatible changes, surface constraint conflicts, or require a user decision.

## MCP API

The MCP boundary distinguishes read-only resources from transactional tools.

### Resources

Illustrative resource paths:

- `composition://project/{project_id}/summary` — identity, tempo map, key regions, active branch, and health
- `composition://project/{project_id}/graph` — normalized semantic graph or a filtered subgraph
- `composition://project/{project_id}/sections` — form, section hierarchy, boundaries, and narrative roles
- `composition://project/{project_id}/motifs` — motif catalog, appearances, transformations, and relationships
- `composition://project/{project_id}/timeline` — semantic events aligned to musical time
- `composition://project/{project_id}/constraints` — active constraints and their scopes
- `composition://project/{project_id}/revisions` — revision history and branch ancestry
- `composition://project/{project_id}/proposals` — pending, accepted, rejected, and superseded alternatives
- `composition://project/{project_id}/provenance/{entity_id}` — origin and change history for an entity
- `composition://project/{project_id}/render-plan/{target}` — current target-specific render plan
- `composition://project/{project_id}/sync/ableton` — mappings, last synchronization, drift, and conflicts
- `composition://project/{project_id}/activity` — agent and system activity stream

Resources should support filters for branch, revision, entity type, section, bar range, and level of detail so agents do not need to load the entire work.

### Tools

Illustrative tool set:

- `analyze_composition` — derive observations without changing state
- `create_proposal` — register one or more candidate graph diffs against a known base revision
- `validate_proposal` — check schema, musical constraints, mapping integrity, and revision compatibility
- `preview_proposal` — compile a proposal into a non-canonical render plan
- `apply_proposal` — atomically accept a validated proposal and create a revision
- `reject_proposal` — close a proposal with an optional reason
- `revert_revision` — create a compensating revision without deleting history
- `create_branch` — fork from a selected revision
- `compare_branches` — return semantic and render-level differences
- `merge_branch` — merge compatible graph diffs and surface unresolved conflicts
- `render_to_ableton` — send a canonical revision or preview to the Ableton adapter
- `inspect_ableton_drift` — compare mapped Ableton state with the last render state
- `resolve_ableton_change` — absorb, preserve as override, regenerate, or fork a detected change

Every mutating call should require a base revision and idempotency key. Responses should include validation results, changed entities, affected constraints, provenance, warnings, and a new revision or proposal ID.

## Agent Roles

- **Conductor** — interprets user intent, selects collaborators, scopes work, coordinates alternatives, and prevents conflicting edits.
- **Composer** — creates and develops motifs, phrases, harmony, counterpoint, and thematic relationships.
- **Arranger** — shapes form, transitions, pacing, repetition, contrast, and sectional development.
- **Performer** — specifies phrasing, articulation, dynamics, groove, timing, and expressive variation.
- **Sound Designer** — proposes timbral roles, instrument families, registers, textures, and device intentions without owning final sound production.
- **Mix Engineer** — reasons about balance, space, focus, masking, and energy at a semantic level; target-specific automation remains a render concern.
- **Critic** — evaluates coherence, novelty, constraint satisfaction, weak transitions, overuse, and unintended consequences.

Agents share the same graph vocabulary but receive role-specific permissions. In the initial product, they produce proposals only; the Conductor cannot bypass validation or human approval.

## Ableton Integration

Ableton Live acts as the primary runtime for auditioning and production.

The adapter maintains stable bidirectional mappings between graph entities and Ableton tracks, clips, scenes, notes, locators, devices, and automation. These mappings must survive re-renders where practical and record when an object has been replaced or orphaned.

### Render Behavior

- Canonical branches render to designated tracks and scenes.
- Alternatives render to separate clips or scenes for rapid A/B comparison.
- Generated objects carry graph IDs through names, metadata, or an adapter-side mapping store.
- Rendering is planned before execution and applied idempotently.
- Partial rendering is supported by section, entity, track, or bar range.
- The adapter reports failed, skipped, and manually protected operations.

### Manual Ableton Changes

When mapped content changes in Ableton, the system records drift and asks for one of four resolutions:

1. **Absorb** — infer and write the meaningful change back into the semantic or performance graph.
2. **Local override** — retain the Ableton edit as target-specific state without changing canonical intent.
3. **Regenerate** — replace the local edit from the canonical graph.
4. **Fork** — preserve the edit as the starting point for a new branch.

Automatic absorption should be limited to changes that can be represented with high confidence. Ambiguous edits remain unresolved until the user decides.

## Read-only UI Concept

The UI should resemble a focused development environment more than a DAW. Its purpose is to answer: What is the composition doing? Why did it change? What is being proposed? What will Ableton render? Where are the conflicts?

### Primary Layout

- **Project navigator:** sections, motifs, harmonic regions, branches, proposals, and target mappings
- **Semantic canvas:** a graph of musical entities and typed relationships
- **Inspector:** plain-language facts, appearances, transformations, constraints, provenance, and target status for the current selection
- **Timeline minimap:** section boundaries, energy and density contours, motif appearances, harmony, and proposal overlays aligned to bars
- **Activity panel:** agent reasoning summaries, validation results, render progress, drift, and warnings
- **Branch and alternative strip:** comparable proposals with concise semantic diffs and audition status

All views are synchronized. Selecting a motif highlights its appearances on the timeline and its mapped clips in Ableton. Selecting a bar range highlights the active sections, motifs, constraints, and recent changes. The UI may offer navigation, filtering, audition triggers, and approve/reject actions, but it should not expose direct note, clip, or graph editing in the MVP.

### Visual Language

- Relationships are labeled with musical verbs, not generic arrows.
- Intent, performance, and render entities have distinct but restrained visual treatments.
- Provenance and confidence are visible without dominating the composition.
- Proposed additions, modifications, and removals use a consistent diff language.
- Uncertainty, constraint violations, and Ableton drift are never hidden behind a generic “sync” indicator.

## User Workflows

### Develop a Musical Idea

1. The user asks, “Develop the chorus without increasing its density.”
2. The Conductor scopes the chorus and delegates bounded proposals.
3. Composer and Arranger agents return alternative semantic diffs.
4. The Critic evaluates constraint satisfaction and downstream effects.
5. Valid alternatives render into separate Ableton scenes.
6. The user auditions them and accepts, rejects, or merges a result.
7. Acceptance creates a canonical revision with provenance.

### Explain the Composition

1. The user selects a motif or section.
2. The UI shows where it appears, how it changes, what it supports, and which constraints affect it.
3. The user can trace the idea from semantic intent through performance choices to rendered Ableton objects.

### Reconcile a Manual Edit

1. A musician edits a generated clip in Ableton.
2. The adapter detects drift from the last render hash.
3. The UI highlights the affected semantic entities and summarizes the change.
4. The user chooses absorb, local override, regenerate, or fork.
5. The chosen resolution is recorded as a transaction.

### Compare Alternatives

1. The user requests multiple approaches to a transition.
2. Each approach becomes a branch with an explanation and graph diff.
3. Each branch renders to a distinct scene.
4. The UI compares changes to tension, harmony, motifs, orchestration, and constraints.
5. The user selects one or merges compatible parts.

## Technical Roadmap

### MVP — Semantic Loop

- Define the graph schema for sections, motifs, appearances, transformations, constraints, and provenance.
- Implement versioned storage, stable IDs, snapshots, and append-only transactions.
- Expose core MCP resources and proposal, validation, apply, revert, and branch tools.
- Support one Conductor and two specialist roles: Composer and Critic.
- Implement deterministic MIDI rendering for a constrained subset of the graph.
- Build an Ableton adapter for tracks, MIDI clips, scenes, and locators.
- Render alternatives into separate scenes.
- Ship a read-only UI with navigator, graph, inspector, timeline, activity, and proposal diff views.
- Require explicit approval for canonical mutations and Ableton reconciliation.

**MVP success test:** A user can request a semantic change, receive two valid alternatives, inspect their rationale and graph diffs, audition both in Ableton, accept one, and revert it without losing history.

### Beta — Round-trip Reliability

- Add performance-layer detail: articulation, dynamics, groove, timing, voicing, and orchestration.
- Add partial rendering and stronger idempotency guarantees.
- Detect Ableton drift and support all four resolution modes.
- Add Arranger and Performer agents.
- Improve branch comparison and semantic merge conflict handling.
- Add constraint authoring through conversation and reusable constraint presets.
- Add project import heuristics that infer an initial graph from an existing Ableton set.
- Instrument latency, render fidelity, proposal acceptance, conflicts, and reversions.

### v1 — Collaborative Composition Environment

- Add Sound Designer and Mix Engineer roles.
- Support multi-agent proposal orchestration with explicit permissions and budgets.
- Expand target coverage to automation, devices, audio references, and richer Ableton metadata.
- Add durable project packaging, migrations, backup, and recovery.
- Support real-time selection synchronization between the UI and Ableton.
- Add branch merge assistance and user-defined evaluation rubrics.
- Publish a stable schema and adapter contract for additional render targets.

## Risks

### Semantic Model Becomes Too Rigid

Music often resists fixed taxonomies. The graph should allow extension, uncertainty, free-form annotations, and gradual formalization rather than requiring every idea to fit a complete ontology.

### Rendered Output Feels Generic

Semantic correctness does not guarantee compelling music. Preserve performer-specific nuance, support controlled randomness, expose render assumptions, and prioritize repeatable comparison over claims of universal musical quality.

### Round-trip Data Loss

Some Ableton edits cannot be cleanly lifted into semantic intent. Never silently absorb ambiguous state. Preserve target overrides and make lossy translations explicit.

### Graph and Ableton Diverge

Stable IDs, render hashes, base revisions, idempotent operations, drift detection, and visible reconciliation state are foundational—not later polish.

### Agent Conflicts and Scope Creep

Specialists may make locally sensible but globally incompatible changes. Require bounded scopes, declared assumptions, validation, Critic review, and Conductor coordination.

### UI Becomes a Second DAW

Direct manipulation would dramatically expand the product surface and create competing sources of truth. Keep the first UI focused on inspection, comparison, navigation, approval, and synchronization health.

### Latency Disrupts Creative Flow

Generate alternatives in parallel where safe, render only affected regions, cache derived state, stream activity, and separate fast structural previews from expensive high-fidelity rendering.

### Trust and Explainability

Users may reject opaque changes even when they sound good. Every proposal should explain its musical intent, affected entities, constraints, and likely audible consequences in concise language.

## Future Extensions

- Additional render targets such as Logic Pro, Bitwig, notation software, game-audio middleware, or standalone MIDI/audio renderers
- Live-performance mode where agents propose safe changes for future scenes without altering currently playing material
- Collaborative human authorship with identity, permissions, review, and attribution
- Reusable compositional vocabularies, house styles, ensemble profiles, and constraint libraries
- Semantic search across a catalog of works and revisions
- Import and analysis of scores, MIDI, stems, and recorded performances
- Evaluation agents for stylistic consistency, playability, accessibility, and orchestration
- Educational views that explain composition and arrangement decisions
- Cross-project motif lineage and versioning
- Model-independent agent protocols so specialist implementations can be swapped or compared
- A plugin ecosystem for graph extensions, render planners, evaluators, and target adapters

---

## Product Boundary

Opus MCP owns musical meaning, structured change, and revision history. Ableton owns detailed production and playback. Agents propose changes through a governed semantic interface. The UI makes the system understandable without becoming an editor.

That boundary is the defining product decision: build the reasoning layer that DAWs do not provide, then let a mature DAW remain the instrument.

