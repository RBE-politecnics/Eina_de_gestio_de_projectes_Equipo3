<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Un estat que bloqueja deixa les tasques sense poder-les canviar (per exemple «Feta»)
        Schema::table('estats', function (Blueprint $table) {
            $table->boolean('bloqueja')->default(false);
        });

        // Noms que demana l'enunciat
        DB::table('estats')->where('nom', 'Pendent')->update(['nom' => 'Per fer']);
        DB::table('estats')->where('nom', 'En curs')->update(['nom' => 'En progrés']);
        DB::table('estats')->where('nom', 'Completada')->update(['nom' => 'Feta', 'bloqueja' => true]);
    }

    public function down(): void
    {
        DB::table('estats')->where('nom', 'Per fer')->update(['nom' => 'Pendent']);
        DB::table('estats')->where('nom', 'En progrés')->update(['nom' => 'En curs']);
        DB::table('estats')->where('nom', 'Feta')->update(['nom' => 'Completada']);

        Schema::table('estats', function (Blueprint $table) {
            $table->dropColumn('bloqueja');
        });
    }
};
