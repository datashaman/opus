<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('project_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('parent_section_id')->nullable()->constrained('sections')->nullOnDelete();
            $table->string('name');
            $table->string('narrative_role')->nullable();
            $table->unsignedInteger('start_bar');
            $table->unsignedInteger('end_bar');
            $table->unsignedInteger('position')->default(0);
            $table->string('lifecycle_state')->default('draft');
            $table->unsignedInteger('version')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
