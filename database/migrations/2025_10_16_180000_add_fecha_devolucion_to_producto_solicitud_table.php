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
        Schema::table('producto_solicitud', function (Blueprint $table) {
            $table->timestamp('fecha_devolucion')->nullable()->after('cantidad');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('producto_solicitud', function (Blueprint $table) {
            $table->dropColumn('fecha_devolucion');
        });
    }
};
