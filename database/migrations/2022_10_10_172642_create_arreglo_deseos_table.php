<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('arreglo_deseos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idCliente')->constrained('personas');
            $table->foreignId('idFlor')->constrained('flores');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('arreglo_deseos');
    }
};
