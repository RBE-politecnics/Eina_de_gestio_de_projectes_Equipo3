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
        Schema::create('projecte__usuaris', function (Blueprint $table) {
            $table->id();
            $table->foreignId('projecte_id');
            $table->foreignId('users_id');
            $table->foreignId('rols_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projecte__usuaris');
    }
};
