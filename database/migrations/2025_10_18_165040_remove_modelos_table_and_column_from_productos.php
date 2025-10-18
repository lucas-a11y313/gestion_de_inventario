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
        Schema::table('productos', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['modelo_id']);
            // Then drop the column
            $table->dropColumn('modelo_id');
        });

        // Finally, drop the 'modelos' table
        Schema::dropIfExists('modelos');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-create the 'modelos' table
        Schema::create('modelos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caracteristica_id')->unique()->constrained('caracteristicas')->onDelete('cascade');
            $table->timestamps();
        });

        // Re-add the 'modelo_id' column to the 'productos' table
        Schema::table('productos', function (Blueprint $table) {
            $table->foreignId('modelo_id')->nullable()->after('marca_id')->constrained('modelos')->onDelete('set null');
        });
    }
};