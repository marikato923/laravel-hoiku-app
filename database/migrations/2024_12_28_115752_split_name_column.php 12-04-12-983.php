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
        Schema::table('children', function (Blueprint $table) {
            // 姓名を分けるカラムを追加
            $table->string('first_name')->after('name');
            $table->string('last_name')->after('first_name');
        });

        // データを分割して新しいカラムに移行
        \DB::table('children')->get()->each(function ($user) {
            // 名前を空白で分割
            [$lastName, $firstName] = explode(' ', $user->name, 2) + [null, null];
            
            \DB::table('children')
                ->where('id', $user->id)
                ->update([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                ]);
        });

        // 古いカラムを削除
        Schema::table('children', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 名前カラムを復元
        Schema::table('children', function (Blueprint $table) {
            $table->string('name')->after('id');
        });

        // データを統合して古いカラムに戻す
        \DB::table('children')->get()->each(function ($user) {
            $fullName = trim($user->last_name . ' ' . $user->first_name);

            \DB::table('children')
                ->where('id', $user->id)
                ->update(['name' => $fullName]);
        });

        // 新しいカラムを削除
        Schema::table('children', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name']);
        });
    }
};