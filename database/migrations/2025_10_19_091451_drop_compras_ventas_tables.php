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
        // Eliminar tablas pivote primero (por las foreign keys)
        Schema::dropIfExists('compra_producto');
        Schema::dropIfExists('producto_venta');

        // Eliminar tablas principales
        Schema::dropIfExists('compras');
        Schema::dropIfExists('ventas');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No se implementa rollback ya que es una eliminación permanente
        // Si se necesita restaurar, usar las migraciones originales
    }
};
