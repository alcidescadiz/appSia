<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $table = 'compras';

    protected $fillable = ['fecha','id_proveedor','id_tipo_pago','total_iva','subtotal', 'total'];

}
