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
        Schema::table('users', function (Blueprint $table) {

            $table->string('first_name')->after('name');
            $table->string('last_name')->after('first_name');
        });

        \DB::table('users')->get()->each(function ($user) {
            [$lastName, $firstName] = explode(' ', $user->name, 2) + [null, null];
            
            \DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->after('id');
        });

        \DB::table('users')->get()->each(function ($user) {
            $fullName = trim($user->last_name . ' ' . $user->first_name);

            \DB::table('users')
                ->where('id', $user->id)
                ->update(['name' => $fullName]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name']);
        });
    }
};
