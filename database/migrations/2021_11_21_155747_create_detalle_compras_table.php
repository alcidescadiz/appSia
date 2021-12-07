<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleComprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_compras', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_compras')->unsigned();
            $table->integer('id_productos')->unsigned();
            $table->double('costo', 8, 2);
            $table->double('cantidad', 8, 2);
            $table->double('iva', 8, 2);
            $table->double('subtotal', 8, 2);
            $table->enum('estatus',['activo','eliminado'])->default('activo');
            $table->foreign('id_productos')->references('id')->on('productos');
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
        Schema::dropIfExists('detalle_compras');
    }
}
