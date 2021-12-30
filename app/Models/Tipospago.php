<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipospago extends Model
{
	use HasFactory;
	
    public $timestamps = true;

    protected $table = 'tipospagos';

    protected $primaryKey = 'id';

    protected $fillable = ['tipo','estatus'];
	
    public function setTipoAttribute($value)
    {
        $this->attributes['tipo']=strtoupper($value);
    }
}
