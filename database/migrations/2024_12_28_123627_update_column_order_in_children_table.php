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

            $table->string('last_kana_name')->after('kana');
            $table->string('first_kana_name')->after('last_kana_name');
        });

        \DB::table('users')->get()->each(function ($user) {
            [$lastKanaName, $firstKanaName] = explode(' ', $user->kana, 2) + [null, null];
            
            \DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'last_kana_name' => $lastKanaName,
                    'first_kana_name' => $firstKanaName,
                ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('kana');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('kana')->after('id');
        });

        \DB::table('users')->get()->each(function ($user) {
            $fullKanaName = trim($user->last_kana_name . ' ' . $user->first_kana_name);

            \DB::table('users')
                ->where('id', $user->id)
                ->update(['kana' => $fullKanaName]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['last_kana_name', 'first_kana_name']);
        });
    }
};
