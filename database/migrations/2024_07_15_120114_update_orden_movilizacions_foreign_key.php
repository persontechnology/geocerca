<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOrdenMovilizacionsForeignKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orden_movilizacions', function (Blueprint $table) {
            $table->dropForeign(['direccion_id']);
            $table->foreign('direccion_id')->references('id')->on('departamentos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orden_movilizacions', function (Blueprint $table) {
            $table->dropForeign(['direccion_id']);
            $table->foreign('direccion_id')->references('id')->on('direccions');
        });
    }
}
