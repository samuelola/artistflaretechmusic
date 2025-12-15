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
        Schema::create('music_releases', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('stereo_id')->nullable();
            $table->string('artist')->nullable();
            $table->date('release_date')->nullable();
            $table->json('meta')->nullable(); // extra JSON
            $table->timestamps();
        });

        Schema::create('artworks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('music_release_id')->constrained('music_releases')->onDelete('cascade');
            $table->string('path');
            $table->string('mime')->nullable();
            $table->timestamps();
        });

        Schema::create('audio_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('music_release_id')->constrained('music_releases')->onDelete('cascade');
            $table->string('filename');
            $table->string('path');
            $table->integer('duration_ms')->nullable(); // duration in milliseconds
            $table->integer('bitrate_kbps')->nullable();
            $table->timestamps();
        });

        Schema::create('tracks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('music_release_id')->constrained('music_releases')->onDelete('cascade');
            $table->foreignId('audio_file_id')->constrained('audio_files')->onDelete('cascade');
            $table->string('title')->nullable();
            $table->integer('track_number')->nullable();
            $table->string('isrc')->nullable()->unique();
            $table->integer('duration_ms')->nullable();
            $table->json('extra')->nullable(); // e.g. composers, labels
            $table->timestamps();
        });

        Schema::create('track_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('track_id')->constrained('tracks')->onDelete('cascade');
            $table->string('participant'); // could reference users table; using string for demo
            $table->string('role');
            $table->string('payout'); // e.g. "50%" or ID linking to payment plan
            $table->timestamps();
        });


        Schema::create('outlets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('music_release_id')->constrained('music_releases')->onDelete('cascade');
            $table->string('outlet_name');
            $table->string('outlet_id')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('music_releases');
        Schema::dropIfExists('outlets');
        Schema::dropIfExists('track_participants');
        Schema::dropIfExists('tracks');
        Schema::dropIfExists('audio_files');
        Schema::dropIfExists('artworks');
        
    }
};
