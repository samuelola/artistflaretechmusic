<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tracks', function (Blueprint $table) {
            $table->string('artist')->nullable()->after('title');
            $table->string('feature_artist')->nullable()->after('artist');
            $table->string('iswc')->nullable()->after('feature_artist');
            $table->string('instrumental')->nullable()->after('iswc');
            $table->string('language')->nullable()->after('instrumental');
            $table->string('parental')->nullable()->after('language');
            $table->json('genre')->nullable()->after('parental');
            $table->json('stream_type')->nullable()->after('genre');
        });
    }

    public function down(): void
    {
        Schema::table('tracks', function (Blueprint $table) {
            $table->dropColumn([
                'artist',
                'feature_artist',
                'iswc',
                'instrumental',
                'language',
                'parental',
                'genre',
                'stream_type',
            ]);
        });
    }
};
