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
        Schema::create('artist_roles_rights', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('artist_ownership_identity_id'); // FK to ArtistOwnerIdenity or users table
            $table->string('role')->nullable();
            $table->string('ownership_type')->nullable(); // 100, co, represent, authorized
            $table->integer('ownership_percentage')->nullable();
            $table->json('co_owners')->nullable(); // [{"name":"John","role":"Producer","percentage":50}]
            $table->timestamps();
            $table->foreign('artist_ownership_identity_id')->references('id')->on('artist_ownership_identity')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artist_roles_rights');
    }
};
