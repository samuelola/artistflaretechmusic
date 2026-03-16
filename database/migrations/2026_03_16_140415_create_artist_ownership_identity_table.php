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
        Schema::create('artist_ownership_identity', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('stage_name');
            $table->date('dob');
            $table->string('nationality');
            $table->string('country');
            $table->string('phone')->unique();
            $table->string('email')->unique();
            
            // Optional promotion links
            $table->string('youtube')->nullable();
            $table->string('instagram')->nullable();
            $table->string('facebook')->nullable();
            $table->string('tiktok')->nullable();

            // Identity verification
            $table->string('id_type');
            $table->string('government_id_path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artist_ownership_identity');
    }
};
