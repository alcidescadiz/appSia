<?php


namespace App\Http\ViewComposer;

use Illuminate\Contracts\View\View;
use App\Models\Cliente;
use Illuminate\Support\Facades\DB;

class DatosClientes
{

    public function compose(View $View){


        $Lclientes = Cliente::all();
        $n_cuentas_pagar= DB::select("select count(*) as total from v_cuentas_por_pagar");
        $n_cuentas_cobrar= DB::select("select count(*) as total from v_cuentas_por_cobrar");
        $View->with(['Lclientes'=> $Lclientes, 'n_cuentas_pagar'=>$n_cuentas_pagar, 'n_cuentas_cobrar'=>$n_cuentas_cobrar]);
    }


}


?>