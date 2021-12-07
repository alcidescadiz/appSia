<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $table = 'detalle_ventas';

    protected $fillable = ['id_ventas','id_productos', 'costo', 'precio_venta','cantidad','subtotal', 'iva', 'estatus'];


}
