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
        Schema::create('provenance_records', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulidMorphs('provenanceable');
            $table->string('action');
            $table->string('source');
            $table->string('source_name')->nullable();
            $table->text('reason')->nullable();
            $table->json('from_state')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provenance_records');
    }
};
