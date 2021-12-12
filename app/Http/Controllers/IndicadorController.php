<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndicadorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type=null)
    {
        $ganancia = DB::select("select sum(ganancias) as ganancia from  v_ganancias");
        $indicadorC = DB::table("v_indicador_compras")->get();
        $indicadorV = DB::table("v_indicador_ventas")->get();
        return view('/indicadores/index', ['indicadorC'=>$indicadorC->toArray(), 'indicadorV'=>$indicadorV->toArray(), 'type'=>$type, 'ganancia'=>$ganancia]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function ganancias()
    {
        $ganancias = DB::table('v_ganancias')->get();
        $ganancia = DB::select("select sum(ganancias) as ganancia from  v_ganancias");
        return view('/indicadores/ganancias', ['ganancias'=>$ganancias->toArray(), 'ganancia'=>$ganancia ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function entrefechas(Request $request)
    {
        $this->validate($request, [
            'fecha1' => 'required',
            'fecha2' => 'required',
        ]);
       
        $fecha1= $request->get('fecha1');
        $fecha2= $request->get('fecha2');
        if ($fecha1<$fecha2) {
            $indicadorC = DB::select("SELECT t.id, t.tipo, t.estatus, sum(c.total) as total
            FROM tipospagos t
            INNER join compras c on t.id=c.id_tipo_pago
            where c.estatus= 'activo'
            and c.fecha >= ? and c.fecha <= ?
            group by t.tipo", [$fecha1, $fecha2]);

            $indicadorV = DB::select("SELECT t.id, t.tipo, t.estatus, sum(v.total) as total
            FROM tipospagos t
            INNER join ventas v on t.id=v.id_tipo_pago
            where v.estatus= 'activo'
            and v.fecha >= ? and v.fecha <= ?
            group by t.tipo", [$fecha1, $fecha2]);

            $ganancias = DB::select("SELECT sum(vdv.ganancia)as ganancia
            FROM v_detalles_ventas vdv
            INNER JOIN v_ventas vv on vv.id= vdv.id_ventas
            WHERE vv.fecha>=? and vv.fecha <= ?", [$fecha1, $fecha2]);
            
            return view('/indicadores/entrefechas', ['indicadorC'=>$indicadorC, 'indicadorV'=>$indicadorV, 'fecha1'=>$fecha1, 'fecha2'=>$fecha2, 'ganancias'=>$ganancias]);
        }
        session()->flash('message', 'La fecha inicial debe ser distinta o menor a la final');
        return redirect('/indicadores/{entrefechas}');

    }


    public function hoy()
    {
        $fecha1= date("Y-m-d");

        $indicadorC = DB::select("SELECT t.id, t.tipo, t.estatus, sum(c.total) as total
        FROM tipospagos t
        INNER join compras c on t.id=c.id_tipo_pago
        where c.estatus= 'activo'
        and c.fecha = ?
        group by t.tipo", [$fecha1]);

        $indicadorV = DB::select("SELECT t.id, t.tipo, t.estatus, sum(v.total) as total
        FROM tipospagos t
        INNER join ventas v on t.id=v.id_tipo_pago
        where v.estatus= 'activo'
        and v.fecha = ?
        group by t.tipo", [$fecha1]);

        $ganancias = DB::select("SELECT sum(vdv.ganancia)as ganancia
        FROM v_detalles_ventas vdv
        INNER JOIN v_ventas vv on vv.id= vdv.id_ventas
        WHERE vv.fecha= ?", [$fecha1]);

        return view('/indicadores/hoy', ['indicadorC'=>$indicadorC, 'indicadorV'=>$indicadorV, 'ganancias'=>$ganancias]);
    }

    public function pordia(Request $request)
    {
        $this->validate($request, [
            'fecha1' => 'required',
        ]);
        $fecha1= $request->get('fecha1');
        $indicadorC = DB::select("SELECT t.id, t.tipo, t.estatus, sum(c.total) as total
        FROM tipospagos t
        INNER join compras c on t.id=c.id_tipo_pago
        where c.estatus= 'activo'
        and c.fecha = ?
        group by t.tipo", [$fecha1]);

        $indicadorV = DB::select("SELECT t.id, t.tipo, t.estatus, sum(v.total) as total
        FROM tipospagos t
        INNER join ventas v on t.id=v.id_tipo_pago
        where v.estatus= 'activo'
        and v.fecha = ?
        group by t.tipo", [$fecha1]);

        $ganancias = DB::select("SELECT sum(vdv.ganancia)as ganancia
        FROM v_detalles_ventas vdv
        INNER JOIN v_ventas vv on vv.id= vdv.id_ventas
        WHERE vv.fecha= ?", [$fecha1]);

        return view('/indicadores/dia', ['indicadorC'=>$indicadorC, 'indicadorV'=>$indicadorV, 'ganancias'=>$ganancias, 'fecha1'=>$fecha1]);
    }

}
