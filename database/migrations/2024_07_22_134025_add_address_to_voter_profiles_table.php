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
        Schema::table('voter_profiles', function (Blueprint $table) {
            $table->string('barangay');
            $table->string('purok')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('voter_profiles', function (Blueprint $table) {
            $table->dropColumn('barangay');
            $table->dropColumn('purok');
        });
    }
};
