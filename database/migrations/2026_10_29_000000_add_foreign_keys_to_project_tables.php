<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasques', function (Blueprint $table) {
            $table->foreign('projecte_id')
                ->references('id')
                ->on('projects')
                ->cascadeOnDelete();
            $table->foreign('users_id')
                ->references('id')
                ->on('Users')
                ->cascadeOnDelete();
            $table->foreign('estats_id')
                ->references('id')
                ->on('estats')
                ->cascadeOnDelete();
        });

        Schema::table('projecte__usuaris', function (Blueprint $table) {
            $table->foreign('projecte_id')
                ->references('id')
                ->on('projects')
                ->cascadeOnDelete();
            $table->foreign('users_id')
                ->references('id')
                ->on('Users')
                ->cascadeOnDelete();
            $table->foreign('rols_id')
                ->references('id')
                ->on('rols')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('tasques', function (Blueprint $table) {
            $table->dropForeign(['projecte_id']);
            $table->dropForeign(['users_id']);
            $table->dropForeign(['estats_id']);
        });

        Schema::table('projecte__usuaris', function (Blueprint $table) {
            $table->dropForeign(['projecte_id']);
            $table->dropForeign(['users_id']);
            $table->dropForeign(['rols_id']);
        });
    }
};
