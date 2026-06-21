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
        Schema::create('world_cup_fixtures', function (Blueprint $table) {
            $table->id();

            // API identifiers
            $table->string('api_match_id')->unique();

            // Match info
            $table->string('group_code')->nullable();
            $table->unsignedTinyInteger('matchday')->nullable();
            $table->string('match_type')->nullable();

            // Teams
            $table->string('home_team_name')->nullable();
            $table->string('away_team_name')->nullable();

            // Scores
            $table->text('home_scorers')->nullable();
            $table->text('away_scorers')->nullable();

            // Scores
            $table->unsignedTinyInteger('home_score')->default(0);
            $table->unsignedTinyInteger('away_score')->default(0);

            // Stadium / host info
            $table->string('stadium_name');
            $table->string('city');
            $table->string('host_country');
            $table->string('timezone');

            // Dates
            $table->timestamp('start_at');

            // Status
            $table->boolean('is_finished')->default(false);

            // For your streaming/match feature
            $table->boolean('is_stream_enabled')->default(false);

            $table->timestamps();

            $table->index('stadium_api_id');
            $table->index('group_code');
            $table->index('matchday');
            $table->index('match_type');
            $table->index('utc_kickoff_at');
            $table->index('is_finished');
            $table->index('is_stream_enabled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('world_cup_fixtures');
    }
};
