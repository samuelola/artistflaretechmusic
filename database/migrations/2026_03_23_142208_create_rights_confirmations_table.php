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
        Schema::create('rights_confirmations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('artist_ownership_identity_id');
            $table->boolean('rights1')->default(false);
            $table->boolean('rights2')->default(false);
            $table->boolean('rights3')->default(false);
            $table->boolean('rights4')->default(false);
            $table->boolean('rights5')->default(false);
            $table->timestamps();
            $table->foreign('artist_ownership_identity_id')->references('id')->on('artist_ownership_identity')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rights_confirmations');
    }
};
