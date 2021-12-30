<?php


namespace App\Http\ViewComposer;

use Illuminate\Contracts\View\View;
use App\Models\Proveedore;


class DatosProveedores
{

    public function compose(View $View){

        $Lproveedores = Proveedore::select('id', 'nombre')->get();
        $View->with('Lproveedores', $Lproveedores);
    }


}


?>