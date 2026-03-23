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
        Schema::create('song_contributors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('artist_owner_song_id');
            $table->string('name');
            $table->string('role');
            $table->unsignedInteger('percentage');
            $table->timestamps();
            $table->foreign('artist_owner_song_id')->references('id')->on('artist_song')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('song_contributors');
    }
};
