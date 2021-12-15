<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class AlmacenController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    public function index()
    {
        $almacen = DB::select("select * from v_almacen");
        return view('/almacen/index', ['almacen'=>$almacen]);
    }

}
