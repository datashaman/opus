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
        Schema::create('transformations', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('motif_appearance_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->json('parameters')->nullable();
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
        Schema::dropIfExists('transformations');
    }
};
