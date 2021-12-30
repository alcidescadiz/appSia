<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Venta extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $table = 'ventas';

    protected $primaryKey = 'id';

    protected $fillable = ['fecha','cliente_id','tipospago_id','total_iva','subtotal', 'total', 'estatus'];

    public function detalle_ventas(){
        return $this->hasMany(DetalleVenta::class, 'id_ventas');
    }  

    public function scopeIdnew($query){
        $codigo= DB::select("SELECT `AUTO_INCREMENT` as codigo FROM  INFORMATION_SCHEMA.TABLES
        WHERE TABLE_SCHEMA = 'appsialaravel'
        AND   TABLE_NAME   = 'ventas'");
        if (count($codigo)) {
            $idnew = $codigo[0]->codigo;
        }else {
            $idnew=1;
        }
        return $idnew;
    }
    public function scopeTotales($query, $id){
        $totales= DB::select("select sum(iva) as tiva,sum(subtotal) as tsubtotal, (sum(iva)+sum(subtotal))as ttotal from detalle_ventas where venta_id = ?", [$id]);
        if ($totales[0]->tiva===null) {
            $tiva= 0;
            $tsubtotal= 0;
            $ttotal= 0;
        }else {
            $tiva= $totales[0]->tiva;
            $tsubtotal= $totales[0]->tsubtotal;
            $ttotal= $totales[0]->ttotal;
        }  
        return [$tiva,$tsubtotal, $ttotal];
    }
}
