<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameDepartamentosAndDireccionsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('departamentos', 'temporary_direccions');
        Schema::rename('direccions', 'departamentos');
        Schema::rename('temporary_direccions', 'direccions');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('direccions', 'temporary_direccions');
        Schema::rename('departamentos', 'direccions');
        Schema::rename('temporary_direccions', 'departamentos');
    }
}
