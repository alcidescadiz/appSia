<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
	use HasFactory;
	
    public $timestamps = true;

    protected $table = 'clientes';

    protected $primaryKey = 'id';

    protected $fillable = ['cedula','nombre','email','direccion','telefono','estatus'];

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

    public function getNombreAttribute($value)
    {
        return strtoupper($value);
    }
    public function getDireccionAttribute($value)
    {
        return strtoupper($value);
    }
    public function getEmailAttribute($value)
    {
        return strtolower($value);
    }
        
}
