<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
	use HasFactory;
	
    public $timestamps = true;

    protected $table = 'productos';

    protected $fillable = ['codigo','nombre','costo','porcentage_ganancia','precio_venta','gravable','tipo','foto', 'estatus'];
	
    public function detalle_compras(){
        return $this->hasOne(DetalleCompra::class, 'id_productos');
    }
    public function detalle_ventas(){
        return $this->hasMany(DetalleVenta::class, 'id_productos');
    }  
    
}
