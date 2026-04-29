<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove the redundant role column (Spatie Permission is used instead)
            $table->dropColumn('role');

            // Change status from free-form string to enum
            $table->enum('status', ['active', 'banned', 'unverified'])
                ->default('active')
                ->change();

            // Add personal profile columns
            $table->string('full_name')->nullable()->after('username');
            $table->string('phone', 20)->nullable()->after('full_name');
            $table->string('avatar')->nullable()->after('phone');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['full_name', 'phone', 'avatar']);
            $table->string('status')->default('active')->change();
            $table->string('role')->default('user');
        });
    }
};
