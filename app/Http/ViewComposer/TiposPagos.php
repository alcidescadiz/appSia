<?php


namespace App\Http\ViewComposer;

use Illuminate\Contracts\View\View;
use App\Models\Tipospago;

class TiposPagos
{

    public function compose(View $View){

        $Ltipospagos = Tipospago::all();
        $View->with('Ltipospagos', $Ltipospagos);
    }


}


?>