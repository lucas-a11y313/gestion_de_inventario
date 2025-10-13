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
        Schema::table('solicitudes', function (Blueprint $table) {
            // Eliminar columnas de comprobante
            $table->dropForeign(['comprobante_id']);
            $table->dropColumn(['comprobante_id', 'numero_comprobante']);

            // Agregar columna de usuario
            $table->foreignId('user_id')->nullable()->after('total')->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('solicitudes', function (Blueprint $table) {
            // Revertir cambios
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');

            // Restaurar columnas originales
            $table->string('numero_comprobante', 255)->nullable();
            $table->foreignId('comprobante_id')->nullable()->constrained('comprobantes')->onDelete('set null');
        });
    }
};
