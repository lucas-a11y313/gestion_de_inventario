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
        Schema::create('compras', function (Blueprint $table) {
            $table->id();
            $table->dateTime('fecha_hora');
            $table->decimal('impuesto',8,2)->unsigned();
            /*
            -decimal('impuesto', 8, 2): Define una columna decimal llamada impuesto con un total de 8 dígitos, de los cuales 2 son después del punto decimal.
            -unsigned(): Asegura que la columna solo almacene valores positivos. 
            */
            $table->tinyInteger('estado')->default(1);
            $table->string('numero_comprobante',255);
            $table->decimal('total',8,2)->unsigned();
            $table->foreignId('comprobante_id')->nullable()->constrained('comprobantes')->onDelete('set null');
            $table->foreignId('proveedore_id')->nullable()->constrained('proveedores')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compras');
    }
};
