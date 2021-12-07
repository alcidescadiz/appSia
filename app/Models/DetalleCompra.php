<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleCompra extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $table = 'detalle_compras';

    protected $fillable = ['id_compras','id_productos','costo','cantidad','subtotal', 'iva', 'estatus'];


}
