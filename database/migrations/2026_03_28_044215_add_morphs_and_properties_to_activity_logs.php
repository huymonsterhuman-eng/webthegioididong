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
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->nullableMorphs('subject'); // Adds subject_type and subject_id
            $table->json('properties')->nullable()->after('description');
            $table->string('action_type')->default('system')->after('action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropMorphs('subject');
            $table->dropColumn('properties');
            $table->dropColumn('action_type');
        });
    }
};
