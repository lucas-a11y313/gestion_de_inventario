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
        Schema::table('adquisiciones', function (Blueprint $table) {
            // Cambiamos la columna 'total' a un DECIMAL(11,2) sin signo (UNSIGNED)
            $table->decimal('total', 11, 2)->unsigned()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('adquisiciones', function (Blueprint $table) {
            // Revertimos al tipo anterior (por si hacés rollback)
            $table->decimal('total', 8, 2)->unsigned()->change();
        });
    }
};
