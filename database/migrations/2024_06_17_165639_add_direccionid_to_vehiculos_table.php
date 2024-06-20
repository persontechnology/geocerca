<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDireccionidToVehiculosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehiculos', function (Blueprint $table) {
            $table->foreignId('direccion_id')->nullable()->constrained('direccions');
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
            $table->dropColumn(['direccion_id']);
        });
    }
}