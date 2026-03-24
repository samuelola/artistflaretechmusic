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
        Schema::create('artist_ownership_payment', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('artist_ownership_identity_id');
             $table->string('payout_method'); // bank | mobile | other

            // BANK
            $table->string('bank_name')->nullable();
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('country')->nullable();

            // MOBILE
            $table->string('mobile_number')->nullable();

            // OTHER
            $table->text('other_info')->nullable();

            $table->timestamps();
            $table->foreign('artist_ownership_identity_id')
            ->references('id')
            ->on('artist_ownership_identity')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artist_ownership_payment');
    }
};
