<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// VALIDO

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasques', function (Blueprint $table) {
            $table->id();
            $table->foreignId('projecte_id');
            $table->foreignId('users_id');
            $table->string('nom');
            $table->timestamps();
            $table->date('data_finalitzacio')->nullable();
            $table->foreignId('estats_id');
            $table->text('Comentaris')->nullable();
            $table->integer('Progres')->default(0);
            $table->string('Color')->default('#FFFFFF')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasques');
    }
};
