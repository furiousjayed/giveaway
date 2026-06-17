<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('giveaways', function (Blueprint $table) {
            $table->index('status');
            $table->index('is_public');
        });

        Schema::table('participants', function (Blueprint $table) {
            $table->index('email');
        });

        Schema::table('winners', function (Blueprint $table) {
            $table->index('rank');
        });
    }

    public function down(): void
    {
        Schema::table('giveaways', function (Blueprint $table) {
            $table->dropIndex('giveaways_status_index');
            $table->dropIndex('giveaways_is_public_index');
        });

        Schema::table('participants', function (Blueprint $table) {
            $table->dropIndex('participants_email_index');
        });

        Schema::table('winners', function (Blueprint $table) {
            $table->dropIndex('winners_rank_index');
        });
    }
};


