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
        // Eliminar campos de la tabla adquisiciones
        Schema::table('adquisiciones', function (Blueprint $table) {
            $table->dropForeign(['comprobante_id']);
            $table->dropColumn(['comprobante_id', 'numero_comprobante']);
        });

        // Eliminar campo precio_venta de la tabla pivot adquisicion_producto
        Schema::table('adquisicion_producto', function (Blueprint $table) {
            $table->dropColumn('precio_venta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restaurar campos en la tabla adquisiciones
        Schema::table('adquisiciones', function (Blueprint $table) {
            $table->foreignId('comprobante_id')->nullable()->after('total')->constrained('comprobantes')->onDelete('set null');
            $table->string('numero_comprobante', 255)->nullable()->after('comprobante_id');
        });

        // Restaurar campo precio_venta en la tabla pivot
        Schema::table('adquisicion_producto', function (Blueprint $table) {
            $table->decimal('precio_venta', 10, 2)->after('precio_compra');
        });
    }
};
