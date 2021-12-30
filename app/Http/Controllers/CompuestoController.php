<?php

namespace App\Http\Controllers;


use App\Models\DetalleCompuesto;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class CompuestoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function compuestos()
    {
        $codigo= DB::select("SELECT `AUTO_INCREMENT` as codigo FROM  INFORMATION_SCHEMA.TABLES
        WHERE TABLE_SCHEMA = 'appsialaravel'
        AND   TABLE_NAME   = 'productos'");
        if (count($codigo)) {
            $idnew = $codigo[0]->codigo;
        }else {
            $idnew=1;
        }
        $totales= DB::select("select sum(iva) as tiva,sum(subtotal) as tsubtotal, (sum(iva)+sum(subtotal))as ttotal from detalle_compuestos where id_compuesto =?", [$idnew]);
        if ($totales[0]->tiva===null) {
            $tiva= 0;
            $tsubtotal= 0;
            $ttotal= 0;
        }else {
            $tiva= $totales[0]->tiva;
            $tsubtotal= $totales[0]->tsubtotal;
            $ttotal= $totales[0]->ttotal;
        } 
		$productos = DB::table('productos')->get();  
        $detalles = DB::table('v_detalles_compuestos')->where('id_compuesto', $idnew)->get();
		return view('compuestos/index', ['idnew'=>$idnew, 'productos'=>$productos->toArray(),'detalles'=>$detalles, 'tiva'=>$tiva,'tsubtotal'=>$tsubtotal,'ttotal'=>$ttotal]);
    }

    public function crearcompuesto(Request $request)
    {
        $this->validate($request, [
            'codigo' => 'unique:productos|required',
            'nombre' => 'unique:productos|required',
            'costoc' => 'required',
            'porcentage_ganancia' => 'required',
            'precio_venta' => 'required',
            'gravable' => 'required',
        ]);
    
        Producto::create([
            'codigo' => $request->get('codigo'),
            'nombre' => $request->get('nombre'),
            'costo' => $request->get('costoc'),
            'porcentage_ganancia' => $request->get('porcentage_ganancia'),   
            'precio_venta' => $request->get('precio_venta'),
            'gravable' => $request->get('gravable'),     
            'tipo' => 'compuesto',     
        ]);
        session()->flash('message', 'El producto compuesto fue creado');
        return redirect('productos');
    }

    public function detallescompuestos(Request $request)
    {

        if ($request->get('producto_id')=== null || $request->get('costo')=== null || $request->get('cantidad')=== null || $request->get('subtotal')=== null) {
            session()->flash('message', 'No hay detalles que registrar');
            session()->flash('alert-class', 'alert alert-danger alert-dismissible fade show');
            return redirect('compuestos');
        } else {
            $id_detalle = $request->get('producto_id');
            $busca = DB::select("select id, gravable, costo from productos where nombre= ?", [$id_detalle]);
            $busca[0]->id;
            $busca[0]->gravable;
            if ($busca[0]->gravable === 'si') {
                $monto_iva =$request->get('costo')*$request->get('cantidad') * 0.16;
            }else {
                $monto_iva = 0;
            }
            $this->validate($request, [
                'producto_id' => 'required',
                'costo' => 'required|numeric',
                'cantidad' => 'required|numeric',
                'subtotal' => 'required|numeric',
            ]);
        
            DetalleCompuesto::create([
                'id_compuesto'=>$request->get('id_compuesto'),
                'producto_id'=>$busca[0]->id,
                'costo' => $request->get('costo'),
                'cantidad' => $request->get('cantidad'),
                'iva' => $monto_iva,
                'subtotal' => $request->get('subtotal'),
                'estatus' => 'activo'
            ]);
            session()->flash('message', 'detalle registrado');
            return redirect('compuestos');
        }
        
    }
    
    public function detallecompuestodestroy($id){
        DB::table('detalle_compuestos')->delete($id);
        return redirect('compuestos');
    }

    public function editcompuesto($id){
        $productocompuesto = DB::table('productos')
        ->where('id', $id)
        ->get(); 

        $totales= DB::select("select sum(iva) as tiva,sum(subtotal) as tsubtotal, (sum(iva)+sum(subtotal))as ttotal from detalle_compuestos where id_compuesto =?",  [$id]);
        if ($totales[0]->tiva===null) {
            $tiva= 0;
            $tsubtotal= 0;
            $ttotal= 0;
            $precio_venta=0;
        }else {
            $tiva= $totales[0]->tiva;
            $tsubtotal= $totales[0]->tsubtotal;
            $ttotal= $totales[0]->tiva + $totales[0]->tsubtotal;
            $precio_venta=  $ttotal+($ttotal * $productocompuesto[0]->porcentage_ganancia/100);
        } 
		$productos = DB::table('productos')->get();  
        $detalles = DB::table('v_detalles_compuestos')->where('id_compuesto', $id)->get();

        return view('compuestos/edit',['idnew'=>$id, 'productocompuesto'=>$productocompuesto->toArray(),'productos'=>$productos->toArray(),'detalles'=>$detalles, 'tiva'=>$tiva,'tsubtotal'=>$tsubtotal,'ttotal'=>$ttotal, 'precio_venta'=>$precio_venta]);
    }
    public function detallescompuestosedit(Request $request) // agregar detalles al ditar
    {
        $id= $request->get('id_compuesto');
        if ($request->get('producto_id')=== null || $request->get('costo')=== null || $request->get('cantidad')=== null || $request->get('subtotal')=== null) {
            session()->flash('message', 'No hay detalles que registrar');
            session()->flash('alert-class', 'alert alert-danger alert-dismissible fade show');
            return $this->editcompuesto($id);
        } else {
            $id_detalle = $request->get('producto_id');
            $busca = DB::select("select id, gravable, costo from productos where nombre=?",  [$id_detalle]);
            $busca[0]->id;
            $busca[0]->gravable;
            if ($busca[0]->gravable === 'si') {
                $monto_iva =$request->get('costo')*$request->get('cantidad') * 0.16;
            }else {
                $monto_iva = 0;
            }
            $this->validate($request, [
                'producto_id' => 'required',
                'costo' => 'required|numeric',
                'cantidad' => 'required|numeric',
                'subtotal' => 'required|numeric',
            ]);
        
            DetalleCompuesto::create([
                'id_compuesto'=>$request->get('id_compuesto'),
                'producto_id'=>$busca[0]->id,
                'costo' => $request->get('costo'),
                'cantidad' => $request->get('cantidad'),
                'iva' => $monto_iva,
                'subtotal' => $request->get('subtotal'),
                'estatus'=> 'activo'
            ]);
            session()->flash('message', 'Detalle registrado');
            session()->flash('alert-class', 'alert alert-success alert-dismissible fade show');
            return $this->editcompuesto($id);
        }
    }

    public function editdetalecompuestodestroy($id){
        $data = DB::select("SELECT id, id_compuesto FROM detalle_compuestos where id = ?", [$id]);
        $id2= $data[0]->id_compuesto;
        DB::table('detalle_compuestos')->delete($id);
        session()->flash('message', 'Detalle eliminado');
        session()->flash('alert-class', 'alert alert-danger alert-dismissible fade show');
        
        $productocompuesto = DB::table('productos')
                    ->where('id', $id2)
                    ->get(); 
        $totales= DB::select("select sum(iva) as tiva,sum(subtotal) as tsubtotal, (sum(iva)+sum(subtotal))as ttotal from detalle_compuestos where id_compuesto =?", [$id2]);
        if ($totales[0]->tiva===null) {
            $tiva= 0;
            $tsubtotal= 0;
            $ttotal= 0;
            $precio_venta=0;
        }else {
            $tiva= $totales[0]->tiva;
            $tsubtotal= $totales[0]->tsubtotal;
            $ttotal= $totales[0]->ttotal;
            $precio_venta=  $ttotal*1 +($ttotal * $productocompuesto[0]->porcentage_ganancia/100);
        } 
		$productos = DB::table('productos')->get();  
        $detalles = DB::table('v_detalles_compuestos')->where('id_compuesto', $id2)->get();

        return view('compuestos/edit',['idnew'=>$id2, 'productocompuesto'=>$productocompuesto->toArray(),'productos'=>$productos->toArray(),'detalles'=>$detalles, 'tiva'=>$tiva,'tsubtotal'=>$tsubtotal,'ttotal'=>$ttotal, 'precio_venta'=>$precio_venta]);
    }
    public function updatecompuesto(Request $request)
    {
        if ($request->get('porcentage_ganancia')=== null || $request->get('foto')=== null) {
            session()->flash('message', 'Verifique que la información esté disponible');
            $id= $request->get('id');
            return $this->editcompuesto($id);
        } else {
            $this->validate($request, [
                'codigo' => 'required',
                'nombre' => 'required',
                'costoc' => 'required',
                'porcentage_ganancia' => 'required|numeric',
                'precio_venta' => 'required',
                'gravable' => 'required',
                'foto' => 'required',
            ]);
            DB::table('productos')
                ->where('id', $request->get('id'))
                ->update([
                    'codigo' => $request->get('codigo'),
                    'nombre' => $request->get('nombre'),
                    'costo' => $request->get('costoc'),
                    'porcentage_ganancia' => $request->get('porcentage_ganancia'),   
                    'precio_venta' => $request->get('precio_venta'),
                    'gravable' => $request->get('gravable'),     
                    'tipo' => 'compuesto',
                    'foto' => $request->get('foto'), 
                ]);
            session()->flash('message', 'El producto compuesto fue actualizado');
            return redirect('productos');
        }  
    }
}
