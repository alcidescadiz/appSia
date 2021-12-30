<?php

namespace App\Http\Controllers;

use App\Models\DetalleVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetalleVentaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'producto_id' => 'required',
            'precio_venta' => 'required|numeric',
            'cantidad' => 'required|numeric',
            'subtotal' => 'required|numeric',
        ]);
        $montoIva= DetalleVenta::iva( $request->get('producto_id'), $request->get('precio_venta'), $request->get('cantidad'));
        DetalleVenta::create([
            'producto_id'=>$montoIva[1],
            'costo'=>$montoIva[2],
            'precio_venta' => $request->get('precio_venta'),
            'cantidad' => $request->get('cantidad'),
            'iva' => $montoIva[0],
            'subtotal' => $request->get('subtotal'),
            'venta_id' => $request->get('venta_id')
        ]);
        return redirect('/facturav');
    }

    public function destroy($id)
    {
        DB::table('detalle_ventas')->delete($id);
        return redirect('/facturav');
    }

    public function detallesedit(Request $request)
    {
        if ($request->get('producto_id')=== null || $request->get('precio_venta')=== null || $request->get('cantidad')=== null || $request->get('subtotal')=== null){
            session()->flash('message', 'No hay detalles que registrar');
        } else {
            $this->validate($request, [
                'producto_id' => 'required',
                'precio_venta' => 'required|numeric',
                'cantidad' => 'required|numeric',
                'subtotal' => 'required|numeric',
            ]);
            $montoIva= DetalleVenta::iva( $request->get('producto_id'), $request->get('precio_venta'), $request->get('cantidad'));
            DetalleVenta::create([
                'producto_id'=>$montoIva[1],
                'costo'=>$montoIva[2],
                'precio_venta' => $request->get('precio_venta'),
                'cantidad' => $request->get('cantidad'),
                'iva' => $montoIva[0],
                'subtotal' => $request->get('subtotal'),
                'venta_id' => $request->get('venta_id')
            ]);
            session()->flash('message', 'Detalle registrado');
        }
            // retorno a la factura de edicion
            return app(VentaController::class)->edit($request->get('venta_id'));
    }

    public function destroyedit($id)
    {
        $id_ventas = DB::select("SELECT id, venta_id FROM detalle_ventas where id = ?", [$id]);
        DB::table('detalle_ventas')->delete($id);
        session()->flash('message', 'Detalle eliminado');
        return app(VentaController::class)->edit($id_ventas[0]->id_ventas);
    }
}
