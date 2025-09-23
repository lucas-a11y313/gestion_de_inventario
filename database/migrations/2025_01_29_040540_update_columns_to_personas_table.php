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
        //Eliminar antigua llave foránea
        Schema::table('personas', function (Blueprint $table) {
            //Si quiero cambiar la relacion entre personas y documentos de 1-1 a 1-N debemos eliminar la columna documento_id que es llave foranea, primero eliminamos la llave foranea y luego la columna para luego volver a crear otra vez la llave foranea

            $table->dropForeign(['documento_id']);
            $table->dropColumn('documento_id');
        });

        //Crear una nueva llave foránea 1-N, 
        Schema::table('personas', function (Blueprint $table) {
            //antes lo que hacia que nuestra relación sea 1-1 era que lo de abajo llevaba ->unique(), ahora lo quitamos el unique de la llave foránea haciendo que sea 1-N

            $table->foreignId('documento_id')->after('estado')->constrained('documentos')->onDelete('cascade');//after('estado') especifica la posición de la columna documento_id, en este caso va a estar después de la columna estado
        });

        //Crear el campo numero_documento
        Schema::table('personas', function (Blueprint $table) {
            $table->string('numero_documento',20)->after('documento_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Eliminar una nueva llave foránea
        Schema::table('personas', function (Blueprint $table) {
            $table->dropForeign(['documento_id']);
            $table->dropColumn('documento_id');
        });

        //Crear la antigua llave foránea RELACIÓN 1-1 colocando ->unique()
        Schema::table('personas', function (Blueprint $table) {
            $table->foreignId('documento_id')->after('estado')->unique()->constrained('documentos')->onDelete('cascade');
        });

        //Eliminar el campo numero_documento
        Schema::table('personas', function (Blueprint $table) {
            $table->dropColumn('numero_documento');
        });
    }
};
