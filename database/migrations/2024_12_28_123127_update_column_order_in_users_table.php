<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN last_name VARCHAR(255) AFTER first_name");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 元の順番を戻す（必要に応じて書き換え）
        DB::statement("ALTER TABLE users MODIFY COLUMN last_name VARCHAR(255) AFTER name");
    }
};
