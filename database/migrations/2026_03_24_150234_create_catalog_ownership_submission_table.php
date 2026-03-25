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
        Schema::create('catalog_ownership_submission', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('artist_ownership_identity_id');
            $table->string('digital_name');
            $table->date('digital_date');
            $table->enum('status', ['pending','approved','rejected'])->default('pending');
            $table->boolean('agree_terms')->default(false);
            $table->boolean('is_submitted')->default(false);
            $table->timestamps();
            $table->foreign('artist_ownership_identity_id', 'co_sub_artist_identity_fk')
            ->references('id')
            ->on('artist_ownership_identity')
            ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalog_ownership_submission');
    }
};
