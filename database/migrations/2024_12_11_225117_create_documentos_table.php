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
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();

            $table->string('tipo_documento',30);
            $table->string('numero_documento',20);
            
            $table->timestamps();/*dentro del timestamps están los create_at y update_at, created_at: Registra cuándo se creó el registro por primera vez.
            updated_at: Registra cuándo se modificó el registro por última vez. Si el registro no ha sido modificado, este campo será igual al valor de created_at.*/
        });
    }

    /**
     * Reverse the migrations.Te permite revertir la migracion
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos');
    }
};
