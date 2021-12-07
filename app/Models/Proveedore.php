<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedore extends Model
{
	use HasFactory;
	
    public $timestamps = true;

    protected $table = 'proveedores';

    protected $fillable = ['rif','nombre','email','direccion','telefono','productos','estatus'];
	
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function compras()
    {
        return $this->hasMany('App\Models\Compra', 'id_proveedor', 'id');
    }
    
}
