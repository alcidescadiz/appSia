<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Compra;

class Proveedore extends Model
{
	use HasFactory;
	
    public $timestamps = true;

    protected $table = 'proveedores';

    protected $primaryKey = 'id';

    protected $fillable = ['rif','nombre','email','direccion','telefono','productos','estatus'];
	
    public function setNombreAttribute($value)
    {
        $this->attributes['nombre']=strtoupper($value);
    }
    public function setEmailAttribute($value)
    {
        $this->attributes['email']=strtolower($value);
    }
    public function setDireccionAttribute($value)
    {
        $this->attributes['direccion']=strtoupper($value);
    }
    public function setProductosAttribute($value)
    {
        $this->attributes['productos']=strtoupper($value);
    }
    public function getEstatusAttribute($value)
    {
        return strtoupper($value);
    }
}
