<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modificar el ENUM de la columna tipo para incluir 'Verificar'
        DB::statement("ALTER TABLE productos MODIFY tipo ENUM('BP', 'Insumo', 'Verificar') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir al ENUM original (esto podría fallar si hay productos con tipo='Verificar')
        DB::statement("ALTER TABLE productos MODIFY tipo ENUM('BP', 'Insumo') NOT NULL");
    }
};
