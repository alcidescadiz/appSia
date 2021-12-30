<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DetalleCompra extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $table = 'detalle_compras';

    protected $primaryKey = 'id';

    protected $fillable = ['compra_id','producto_id','costo','cantidad','iva', 'subtotal', 'estatus'];

    public function scopeIva($query, $producto_id, $costo, $cantidad){
        $busca = DB::select('SELECT id, gravable from productos where nombre= ?', [$producto_id]);
        if ($busca[0]->gravable === 'si') {
            $monto_iva = $costo*  $cantidad * 0.16;
        }else {
            $monto_iva = 0;
        }
        return [$monto_iva, $busca[0]->id];
    }

}
