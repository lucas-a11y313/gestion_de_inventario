<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrar datos existentes de inventariobps a inventariobp_user
        $inventarioBPs = DB::table('inventariobps')->get();

        foreach ($inventarioBPs as $bp) {
            DB::table('inventariobp_user')->insert([
                'inventariobp_id' => $bp->id,
                'user_id' => $bp->user_id,
                'asignado_por' => null, // No sabemos quién lo asignó inicialmente
                'fecha_desasignacion' => null, // Está activo
                'created_at' => $bp->created_at ?? now(),
                'updated_at' => $bp->updated_at ?? now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Limpiar la tabla pivot
        DB::table('inventariobp_user')->truncate();
    }
};
