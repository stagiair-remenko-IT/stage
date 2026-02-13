<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ratings', function (Blueprint $table) {
            $table->string('age_range', 20)->nullable()->after('location');
            $table->string('gender', 20)->nullable()->after('age_range');
            $table->unsignedTinyInteger('score_numeric')->nullable()->after('score'); // 1-5 for Looker Studio
        });

        // Backfill score_numeric for existing rows
        $map = ['very_bad' => 1, 'bad' => 2, 'neutral' => 3, 'good' => 4, 'very_good' => 5];
        foreach ($map as $score => $num) {
            DB::table('ratings')->where('score', $score)->update(['score_numeric' => $num]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ratings', function (Blueprint $table) {
            $table->dropColumn(['age_range', 'gender', 'score_numeric']);
        });
    }
};
