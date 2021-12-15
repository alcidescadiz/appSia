<?php

namespace App\Http\Controllers;

use App\Models\Cuenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CuentaController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    public function pagar()
    {
        $pagar = DB::table('v_cuentas_por_pagar')->get();
        return view('/cuentas/pagar', ['pagar'=>$pagar->toArray()]);
    }
    public function cobrar()
    {
        $cobrar = DB::table('v_cuentas_por_cobrar')->get();
        return view('/cuentas/cobrar', ['cobrar'=>$cobrar->toArray()]);
    }
    public function updatepagar(Request $request,$id)
    {
        DB::table('cuentas')->where('id', $id)
        ->update([ 'estatus' => 'cancelado']);
        return redirect('/pagar');
    }
    public function updatecobrar(Request $request,$id)
    {
        DB::table('cuentas') ->where('id', $id)
        ->update(['estatus' => 'cancelado']);
        return redirect('/cobrar');
    }
}
