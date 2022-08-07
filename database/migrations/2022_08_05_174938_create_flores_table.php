<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * TODO: Pendiente agregar descrpcion grande
     * @return void
     */
    public function up()
    {
        Schema::create('flores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('descripcion')->nullable();
            $table->string('detalles')->nullable();
            $table->integer('precioFinal');
            $table->integer('descuento')->default(0);
            $table->integer('precioInicial')->default(0);
            $table->integer('stock');
            $table->integer('numVentas')->default(0);
            $table->foreignId('idVendedor')->constrained('stores');
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
        Schema::dropIfExists('flores');
    }
};
