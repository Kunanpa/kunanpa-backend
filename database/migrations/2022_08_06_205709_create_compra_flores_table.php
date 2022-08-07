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
        Schema::create('compra_flores', function (Blueprint $table) {
            $table->id();
            $table->integer('cantidad');
            $table->string('costo');
            $table->string('estado')->default('pendiente');
            $table->foreignId('idCompra')->constrained('compras');
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
        Schema::dropIfExists('compra_flores');
    }
};
