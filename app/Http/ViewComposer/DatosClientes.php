<?php


namespace App\Http\ViewComposer;

use Illuminate\Contracts\View\View;
use App\Models\Cliente;
use Illuminate\Support\Facades\DB;

class DatosClientes
{

    public function compose(View $View){

        $Lclientes = Cliente::select('id', 'nombre')->get();
        $View->with(['Lclientes'=> $Lclientes]);
    }


}


?>