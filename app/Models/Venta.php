<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $table = 'ventas';

    protected $fillable = ['fecha','id_cliente','id_tipo_pago','total_iva','subtotal', 'total', 'estatus'];

    public function detalle_ventas(){
        return $this->hasMany(DetalleVenta::class, 'id_ventas');
    }  

}
