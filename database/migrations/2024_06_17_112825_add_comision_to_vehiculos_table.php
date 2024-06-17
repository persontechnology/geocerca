<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddComisionToVehiculosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehiculos', function (Blueprint $table) {
            $table->integer('numero_ocupantes')->nullable();
            $table->string('procedencia')->nullable();
            $table->string('destino')->nullable();
            $table->string('comision_cumplir')->nullable();
            $table->string('actividad_cumplir')->nullable();

        });

        Schema::table('orden_movilizacions', function (Blueprint $table) {
            $table->string('actividad_cumplir')->nullable();

        });

    }

    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehiculos', function (Blueprint $table) {
            $table->dropColumn(['numero_ocupantes','procedencia','destino','comision_cumplir','actividad_cumplir']);
        });

        Schema::table('orden_movilizacions', function (Blueprint $table) {
            $table->dropColumn(['actividad_cumplir']);
        });
    }
}
