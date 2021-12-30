<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
	use HasFactory;
	
    public $timestamps = true;

    protected $table = 'productos';

    protected $primaryKey = 'id';

    protected $fillable = ['codigo','nombre','costo','porcentage_ganancia','precio_venta','gravable','tipo','foto', 'estatus'];

    public function setNombreAttribute($value)
    {
        $this->attributes['nombre']=strtoupper($value);
    }
    public function setCodigoAttribute($value)
    {
        $this->attributes['codigo']=strtoupper($value);
    }
}
