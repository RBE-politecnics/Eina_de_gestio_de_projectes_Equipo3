<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipus_tasca', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('color')->default('#007BFF');
        });

        // Tipus que venen per defecte
        DB::table('tipus_tasca')->insert([
            ['nom' => 'Disseny', 'color' => '#a855f7'],
            ['nom' => 'Front-end', 'color' => '#3b82f6'],
            ['nom' => 'Back-end', 'color' => '#22c55e'],
            ['nom' => 'Base de dades', 'color' => '#f59e0b'],
            ['nom' => 'QA', 'color' => '#FF6B6B'],
        ]);

        // Si es borra un tipus, les tasques es queden sense tipus
        Schema::table('tasques', function (Blueprint $table) {
            $table->foreignId('tipus_id')->nullable()->constrained('tipus_tasca')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('tasques', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tipus_id');
        });

        Schema::dropIfExists('tipus_tasca');
    }
};
