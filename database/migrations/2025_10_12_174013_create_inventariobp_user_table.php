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
        Schema::create('inventariobp_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventariobp_id')->constrained('inventariobps')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('asignado_por')->nullable()->constrained('users')->onDelete('set null');
            $table->date('fecha_desasignacion')->nullable();
            $table->timestamps(); // created_at = fecha de asignación
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventariobp_user');
    }
};
