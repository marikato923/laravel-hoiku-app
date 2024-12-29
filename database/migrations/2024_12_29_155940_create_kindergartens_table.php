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
        Schema::create('kindergartens', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('postal_code');
            $table->string('address');
            $table->string('principal');
            $table->string('establishment_date');
            $table->string('number_of_employees');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kindergartens');
    }
};
