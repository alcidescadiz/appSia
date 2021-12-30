<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DetalleVenta extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $table = 'detalle_ventas';

    protected $primaryKey = 'id';

    protected $fillable = ['venta_id','producto_id', 'costo', 'precio_venta','cantidad','subtotal', 'iva', 'estatus'];

    public function scopeIva($query, $producto_id, $precio_venta, $cantidad){
        $busca = DB::select('SELECT id, gravable, costo from productos where nombre= ?', [$producto_id]);
        if ($busca[0]->gravable === 'si') {
            $monto_iva = $precio_venta*  $cantidad * 0.16;
        }else {
            $monto_iva = 0;
        }
        return [$monto_iva, $busca[0]->id, $busca[0]->costo];
    }

}
