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
        Schema::table('admin', function (Blueprint $table) {

            $table->string('last_name')->after('id');
            $table->string('first_name')->after('last_name');
        });


        Schema::table('admin', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admin', function (Blueprint $table) {
            $table->string('name')->after('id');
        });

        Schema::table('admin', function (Blueprint $table) {
            $table->dropColumn(['last_name', 'first_name']);
        });
    }
};