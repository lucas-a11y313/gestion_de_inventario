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
            $table->enum('tipo', ['BP', 'Insumo'])->default('Insumo')->after('marca_id');
            $table->string('ubicacion', 100)->nullable()->after('tipo');
            $table->string('origen', 100)->nullable()->after('ubicacion');
            $table->text('sugerencia')->nullable()->after('origen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn(['tipo', 'ubicacion', 'origen', 'sugerencia']);
        });
    }
};
