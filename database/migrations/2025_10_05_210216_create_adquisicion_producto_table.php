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
        Schema::create('adquisicion_producto', function (Blueprint $table) {
            $table->id();
            $table->integer('cantidad')->unsigned();
            $table->decimal('precio_compra',10,2);
            $table->decimal('precio_venta',10,2);
            $table->foreignId('adquisicion_id')->constrained('adquisiciones')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adquisicion_producto');
    }
};
