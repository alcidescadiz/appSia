<?php


namespace App\Http\ViewComposer;

use Illuminate\Contracts\View\View;
use App\Models\Cliente;
use Illuminate\Support\Facades\DB;

class TotalCuentas
{

    public function compose(View $View){

        $n_cuentas_pagar= DB::select("select count(*) as total from v_cuentas_por_pagar where estatus='pendiente'");
        $n_cuentas_cobrar= DB::select("select count(*) as total from v_cuentas_por_cobrar where estatus='pendiente'");
        $View->with(['n_cuentas_pagar'=>$n_cuentas_pagar, 'n_cuentas_cobrar'=>$n_cuentas_cobrar]);
    }


}


?>