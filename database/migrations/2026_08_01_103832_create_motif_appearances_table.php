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
        Schema::create('motif_appearances', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('motif_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('section_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('start_bar');
            $table->unsignedInteger('end_bar');
            $table->text('description')->nullable();
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
        Schema::dropIfExists('motif_appearances');
    }
};
