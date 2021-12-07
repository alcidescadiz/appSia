<?php


namespace App\Http\ViewComposer;

use Illuminate\Contracts\View\View;
use App\Models\Proveedore;


class DatosProveedores
{

    public function compose(View $View){

        $Lproveedores = Proveedore::all();
        $View->with('Lproveedores', $Lproveedores);
    }


}


?>