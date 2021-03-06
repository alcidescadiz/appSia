<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleCompuesto extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $table = 'detalle_compuestos';
    
    protected $primaryKey = 'id';

    protected $fillable = ['id_compuesto', 'producto_id','costo','cantidad','subtotal', 'iva', 'estatus'];
}
