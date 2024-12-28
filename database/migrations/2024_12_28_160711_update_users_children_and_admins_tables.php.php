<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersChildrenAndAdminsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('last_name')->after('id');
            $table->string('first_name')->after('last_name');
            $table->string('last_kana_name')->after('first_name');
            $table->string('first_kana_name')->after('last_kana_name');
            $table->dropColumn('name');
            $table->dropColumn('kana');
        });

        // children table
        Schema::table('children', function (Blueprint $table) {
            $table->string('last_name')->after('id');
            $table->string('first_name')->after('last_name');
            $table->string('last_kana_name')->after('first_name');
            $table->string('first_kana_name')->after('last_kana_name');
            $table->dropColumn('name');
            $table->dropColumn('kana');
        });

        //  admins table
        Schema::table('admins', function (Blueprint $table) {
            $table->string('last_name')->after('id');
            $table->string('first_name')->after('last_name');
            $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['last_name', 'first_name', 'last_kana_name', 'first_kana_name']);
            $table->string('name')->after('id');
            $table->string('kana')->after('name');
        });

        // children table
        Schema::table('children', function (Blueprint $table) {
            $table->dropColumn(['last_name', 'first_name', 'last_kana_name', 'first_kana_name']);
            $table->string('name')->after('id');
            $table->string('kana')->after('name');
        });

        // admins table
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn(['last_name', 'first_name']);
            $table->string('name')->after('id');
        });
    }
}