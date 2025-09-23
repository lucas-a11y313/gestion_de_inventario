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
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('persona_id')->unique()->constrained('personas')->onDelete('cascade');
            /* 
            -constrained('personas'): Crea una restricción de clave foránea en persona_id que apunta a la tabla personas. Por defecto, se vincula al campo id de la tabla personas.
            -El metodo onDelete('cascade') hace esto: Define que, si un registro relacionado en la tabla personas es eliminado, automáticamente se eliminarán los registros relacionados en la tabla actual (en este caso, podría ser la tabla proveedores). 
            */
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proveedores');
    }
};
