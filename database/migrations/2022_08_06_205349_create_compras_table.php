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
        Schema::create('compras', function (Blueprint $table) {
            $table->id();
            $table->string('nombres');
            $table->integer('numTelefono');
            $table->string('direccion');
            $table->string('distrito');
            $table->integer('codigoPostal');
            $table->string('pais');
            $table->string('nota', 500)->nullable();
            $table->foreignId('idCliente')->constrained('personas');
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
        Schema::dropIfExists('compras');
    }
};
