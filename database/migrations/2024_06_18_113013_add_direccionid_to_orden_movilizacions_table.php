<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDireccionidToOrdenMovilizacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orden_movilizacions', function (Blueprint $table) {
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
        Schema::table('orden_movilizacions', function (Blueprint $table) {
            $table->dropColumn(['direccion_id']);
        });
    }
}
