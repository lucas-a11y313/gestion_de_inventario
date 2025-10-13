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
            // Eliminar columnas
            $table->dropForeign(['proveedore_id']);
            $table->dropColumn(['proveedore_id', 'total']);

            // Agregar nuevas columnas
            $table->string('tipo_solicitud', 50)->after('fecha_hora'); // 'retiro' o 'prestamo'
            $table->text('razon')->nullable()->after('tipo_solicitud'); // razón del retiro o préstamo
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('solicitudes', function (Blueprint $table) {
            // Revertir cambios
            $table->dropColumn(['tipo_solicitud', 'razon']);

            // Restaurar columnas originales
            $table->decimal('total', 8, 2)->unsigned()->nullable();
            $table->foreignId('proveedore_id')->nullable()->constrained('proveedores')->onDelete('set null');
        });
    }
};
