<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCuentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuentas', function (Blueprint $table) {
            $table->id();
            $table->integer('codigo')->unsigned()->required();
            $table->enum('tipo',['compras','ventas'])->default('compras');
            $table->date('fecha_pago')->required();
            $table->enum('estatus',['pendiente','cancelado'])->default('pendiente');
            $table->integer('id_tipo_pago')->unsigned()->required();
            $table->foreign('id_tipo_pago')->references('id')->on('tipospagos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cuentas');
    }
}
