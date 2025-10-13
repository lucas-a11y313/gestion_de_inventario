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
        Schema::table('inventariobps', function (Blueprint $table) {
            // Remover la primary key del campo id string
            $table->dropPrimary(['id']);
            // Remover el campo id string
            $table->dropColumn('id');
        });

        Schema::table('inventariobps', function (Blueprint $table) {
            // Agregar id auto-incremental como en productos
            $table->id()->first();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventariobps', function (Blueprint $table) {
            // Remover el id auto-incremental
            $table->dropColumn('id');
        });

        Schema::table('inventariobps', function (Blueprint $table) {
            // Restaurar el id string como primary key
            $table->string('id', 50)->primary()->first();
        });
    }
};
