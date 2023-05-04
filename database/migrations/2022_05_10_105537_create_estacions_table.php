<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstacionsTable extends Migration
{
    
    public function up()
    {
        Schema::create('estacions', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->polygon('area')->nullable();
            $table->timestamps();
            
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('estacions');
    }
}
