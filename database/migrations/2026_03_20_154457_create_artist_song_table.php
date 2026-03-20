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
        Schema::create('artist_song', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('artist_ownership_identity_id');
            $table->string('title');
            $table->string('artist_name');
            $table->year('release_year');
            $table->string('genre');
            $table->string('duration');
            $table->enum('distribution_status', ['released','unreleased','previously_distributed']);
            $table->string('spotify_link')->nullable();
            $table->string('apple_link')->nullable();
            $table->string('audiomack_link')->nullable();
            $table->string('youtube_link')->nullable();
            $table->string('file_path'); // stored uploaded file
            $table->timestamps();
            $table->foreign('artist_ownership_identity_id')->references('id')->on('artist_ownership_identity')->onDelete('cascade');
         
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artist_song');
    }
};
