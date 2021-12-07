<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Productos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo')->unique()->required();
            $table->string('nombre')->unique()->required();
            $table->double('costo',8,2)->required();
            $table->double('porcentage_ganancia',8,2)->required();
            $table->double('precio_venta',8,2)->required(); 
            $table->enum('gravable',['si','no'])->default('no')->required();
            $table->enum('tipo',['unitario','compuesto'])->default('unitario');
            $table->enum('estatus',['activo','eliminado'])->default('activo');
            $table->char('foto', 100)->default('https://www.ecvegasoft.com.mx/images/product/tienda/sinfoto.png');
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
        //
    }
}
