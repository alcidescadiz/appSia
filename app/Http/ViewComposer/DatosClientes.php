<?php


namespace App\Http\ViewComposer;

use Illuminate\Contracts\View\View;
use App\Models\Cliente;


class DatosClientes
{

    public function compose(View $View){

        $Lclientes = Cliente::all();
        $View->with('Lclientes', $Lclientes);
    }


}


?>