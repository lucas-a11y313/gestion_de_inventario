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
            $table->string('codigo', 50)->nullable()->change();
            $table->foreignId('marca_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            // Assuming no null data has been inserted in these columns
            // Reverting might fail if there are products with null codigo or marca_id
            $table->string('codigo', 50)->nullable(false)->change();
            $table->foreignId('marca_id')->nullable(false)->change();
        });
    }
};
