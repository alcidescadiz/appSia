<?php

namespace App\Http\Controllers;

use App\Models\DetalleCompra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetalleCompraController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    public function store(Request $request){
        $this->validate($request, [
            'producto_id' => 'required',
            'costo' => 'required|numeric',
            'cantidad' => 'required|numeric',
            'subtotal' => 'required|numeric',
        ]);
        $montoIva= DetalleCompra::iva( $request->get('producto_id'), $request->get('costo'), $request->get('cantidad'));
        DetalleCompra::create([
            'compra_id' => $request->get('compra_id'),
            'producto_id'=>$montoIva[1],
            'costo' => $request->get('costo'),
            'cantidad' => $request->get('cantidad'),
            'iva' => $montoIva[0],
            'subtotal' => $request->get('subtotal'),
        ]);
        session()->flash('message', 'Detalle a su compra ha sido agregado'); 
        return redirect('/compras.create');
    }
    public function destroy($id){
        DB::table('detalle_compras')->delete($id);
        session()->flash('message', 'Detalle eliminado');
        return redirect('/compras.create');
    }
    public function detallesedit(Request $request){
        if ($request->get('producto_id')=== null || $request->get('costo')=== null || $request->get('cantidad')=== null || $request->get('subtotal')=== null) {
            session()->flash('message', 'No hay detalles que registrar');
        } else {
            $this->validate($request, [
                'id_productos' => 'required',
                'costo' => 'required|numeric',
                'cantidad' => 'required|numeric',
                'subtotal' => 'required|numeric',
            ]);
            $montoIva= DetalleCompra::iva( $request->get('producto_id'), $request->get('costo'), $request->get('cantidad'));
            DetalleCompra::create([
                'producto_id'=>$montoIva[1],
                'costo'=>$request->get('costo'),
                'cantidad' => $request->get('cantidad'),
                'iva' => $montoIva[0],
                'subtotal' => $request->get('subtotal'),
                'compra_id' => $request->get('compra_id'),
            ]);
            session()->flash('message', 'Detalle registrado');
        }
       return app(CompraController::class)->edit($request->get('compra_id'));
    }
    public function destroyedit($id){
        DB::table('detalle_compras')->delete($id);
        $compra_id = DB::select("SELECT id, compra_id FROM detalle_compras where id =?", [$id]);
        $compra_id= $compra_id[0]->compra_id;
        session()->flash('message', 'Detalle eliminado');
        return app(CompraController::class)->edit($compra_id);
    }
}
