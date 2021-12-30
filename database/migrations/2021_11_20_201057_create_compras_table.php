<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compras', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha')->required();
            $table->integer('proveedore_id')->unsigned()->required();
            $table->integer('tipospago_id')->unsigned()->required();
            $table->double('total_iva', 8, 2)->required();
            $table->double('subtotal', 8, 2)->required();
            $table->double('total', 8, 2)->required();
            $table->enum('estatus',['activo','eliminado'])->default('activo');
            $table->foreign('proveedore_id')->references('id')->on('proveedores');
            $table->foreign('tipospago_id')->references('id')->on('tipospagos');
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
}
