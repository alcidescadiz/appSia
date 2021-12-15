<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\DetalleCompuesto;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use vendor\autoload;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CompraController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    
    public function index(){
        $compras = DB::table('v_compras')->paginate(5);
        return view('/compras/index', ['compras'=>$compras]);
    }
    public function store(Request $request){
       return  $this->validate($request, [
            'fecha' => 'required',
            'id_proveedor' => 'required',
            'id_tipo_pago' => 'required',
            'total_iva' => 'required|numeric',
            'subtotal' => 'required|numeric',
            'total' => 'required|numeric',
            ]);
        Compra::create([
                'fecha' => $request->get('fecha'),
                'id_proveedor' => $request->get('id_proveedor'),
                'id_tipo_pago' => $request->get('id_tipo_pago'),
                'total_iva' => $request->get('total_iva'),
                'subtotal' => $request->get('subtotal'),
                'total' => $request->get('total'),
                'estatus' => 'activo'
            ]);
        session()->flash('message', 'La compra ha sido registrada');
        return redirect('compras');
    }
    public function detallestore(Request $request){
        $this->validate($request, [
            'id_productos' => 'required',
            'costo' => 'required|numeric',
            'cantidad' => 'required|numeric',
            'subtotal' => 'required|numeric',
        ]);

        $id_detalle = $request->get('id_productos');
        //dd($id_detalle);
        $busca = DB::select('SELECT id, gravable from productos where nombre= ?', [$id_detalle]);
        $busca[0]->id;
        $busca[0]->gravable;
        if ($busca[0]->gravable === 'si') {
            $monto_iva =$request->get('costo')*$request->get('cantidad') * 0.16;
        }else {
            $monto_iva = 0;
        }
        DetalleCompra::create([
            'id_productos'=>$busca[0]->id,
            'costo' => $request->get('costo'),
            'cantidad' => $request->get('cantidad'),
            'iva' => $monto_iva,
            'subtotal' => $request->get('subtotal'),
            'id_compras' => $request->get('id_compras')
        ]);
        session()->flash('message', 'Detalle a su compra ha sido agregado'); 
        return redirect('/compras.create');
    }

    public function detallesedit(Request $request)
    {
        if ($request->get('id_productos')=== null || $request->get('costo')=== null || $request->get('cantidad')=== null || $request->get('subtotal')=== null) {
            session()->flash('message', 'No hay detalles que registrar');
        } else {
            $this->validate($request, [
                'id_productos' => 'required',
                'costo' => 'required|numeric',
                'cantidad' => 'required|numeric',
                'subtotal' => 'required|numeric',
            ]);
            $id_detalle = $request->get('id_productos');
            //dd($id_detalle);
            $busca = DB::select('SELECT id, gravable from productos where nombre= ?', [$id_detalle]);
            $busca[0]->id;
            $busca[0]->gravable;
            if ($busca[0]->gravable === 'si') {
                $monto_iva =$request->get('costo')*$request->get('cantidad') * 0.16;
            }else {
                $monto_iva = 0;
            }
            DetalleCompra::create([
                'id_productos'=>$busca[0]->id,
                'costo' => $request->get('costo'),
                'cantidad' => $request->get('cantidad'),
                'iva' => $monto_iva,
                'subtotal' => $request->get('subtotal'),
                'id_compras' => $request->get('id_compras')
            ]);
            session()->flash('message', 'Detalle registrado');
        }
        // factura 
        // coodigo proveedores productos y tipo de pago:
        $id= $request->get('id_compras');
        $productos = DB::table('productos')->get();  
        $totales= DB::select("select sum(iva) as tiva,sum(subtotal) as tsubtotal, (sum(iva)+sum(subtotal))as ttotal from detalle_compras where id_compras = ?",[$id]);
        if ($totales[0]->tiva===null) {
            $tiva= 0;
            $tsubtotal= 0;
            $ttotal= 0;
        }else {
            $tiva= $totales[0]->tiva;
            $tsubtotal= $totales[0]->tsubtotal;
            $ttotal= $totales[0]->ttotal;
        }
        $detalles = DB::table('v_detalles_compras')->where('id_compras', $id)->get();
        $compras= DB::select("SELECT * FROM v_compras where id =?", [$id]);
        return view('compras/update',['compras'=>$compras, 'id'=>$id, 'productos'=>$productos->toArray(), 'tiva'=>$tiva,'tsubtotal'=>$tsubtotal,'ttotal'=>$ttotal, 'detalles'=>$detalles]);
    }

    public function show(){
        // factura 
        $codigo= DB::select("SELECT `AUTO_INCREMENT` as codigo FROM  INFORMATION_SCHEMA.TABLES
        WHERE TABLE_SCHEMA = 'appsialaravel'
        AND   TABLE_NAME   = 'compras'");
        if (count($codigo)) {
            $idnew = $codigo[0]->codigo;
        }else {
            $idnew=1;
        }
        $totales= DB::select("select sum(iva) as tiva,sum(subtotal) as tsubtotal, (sum(iva)+sum(subtotal))as ttotal from detalle_compras where id_compras = ?", [$idnew]);
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
        $detalles = DB::table('v_detalles_compras')->where('id_compras', $idnew)->get();
        return view('/compras.create', [ 'productos'=>$productos->toArray(), 'idnew'=>$idnew, 'detalles'=>$detalles, 'tiva'=>$tiva,'tsubtotal'=>$tsubtotal,'ttotal'=>$ttotal]);
    }
    
    public function edit($id)
    {
        $productos = DB::table('productos')->get();  
        $totales= DB::select("select sum(iva) as tiva,sum(subtotal) as tsubtotal, (sum(iva)+sum(subtotal))as ttotal from detalle_compras where id_compras =?", [$id]);
        //DD($totales);
        if ($totales[0]->tiva===null) {
            $tiva= 0;
            $tsubtotal= 0;
            $ttotal= 0;
        }else {
            $tiva= $totales[0]->tiva;
            $tsubtotal= $totales[0]->tsubtotal;
            $ttotal= $totales[0]->ttotal;
        }
        $detalles = DB::table('v_detalles_compras')->where('id_compras', $id)->get();
        $compras= DB::select("SELECT * FROM v_compras where id =?", [$id]);
        return view('compras/update',['compras'=>$compras, 'id'=>$id, 'productos'=>$productos->toArray(), 'tiva'=>$tiva,'tsubtotal'=>$tsubtotal,'ttotal'=>$ttotal, 'detalles'=>$detalles]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Compra  $compra
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
       if ($request->get('fecha')) {
           $this->validate($request, [
               'fecha' => 'required',
               'id_proveedor' => 'required',
               'id_tipo_pago' => 'required',
               'total_iva' => 'required|numeric',
               'subtotal' => 'required|numeric',
               'total' => 'required|numeric',
               ]);
               DB::table('compras')
                   ->where('id',$request->get('id'))
                   ->update([
                       'fecha' => $request->get('fecha'),
                       'id_proveedor' => $request->get('id_proveedor'),
                       'id_tipo_pago' => $request->get('id_tipo_pago'),
                       'total_iva' => $request->get('total_iva'),
                       'subtotal' => $request->get('subtotal'),
                       'total' => $request->get('total'),
                   ]);
               
               session()->flash('message', 'Compra ha sido editada');
               return redirect('compras');
       }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Compra  $compra
     * @return \Illuminate\Http\Response
     */
    public function compra_destroy($id)
    {
        DB::table('compras')
            ->where('id', $id)
            ->update([
                'estatus' => 'eliminado',
            ]);
        DB::table('detalle_compras')
            ->where('id_compras', $id)
            ->update([
                'estatus' => 'eliminado',
            ]);
        DB::table('cuentas')
            ->where('codigo', $id)
            ->where('tipo', 'compras')
            ->update([
                'estatus' => 'cancelado',
            ]);
        session()->flash('message', 'La compra ha sido eliminada');
        return redirect('/compras');
    }
    public function detalledestroy($id)
    {
        DB::table('detalle_compras')->delete($id);
        session()->flash('message', 'Detalle eliminado');
        return redirect('/compras');
    }
    public function detalledestroyedit($id)
    {
        $id_compras = DB::select("SELECT id, id_compras FROM detalle_compras where id =?", [$id]);
        $id_compras= $id_compras[0]->id_compras;
        DB::table('detalle_compras')->delete($id);
       // factura 
        // coodigo proveedores productos y tipo de pago:
        $id= $id_compras; 
        $productos = DB::table('productos')->get();  
        $totales= DB::select("select sum(iva) as tiva,sum(subtotal) as tsubtotal, (sum(iva)+sum(subtotal))as ttotal from detalle_compras where id_compras = ?", [$id]);
        if ($totales[0]->tiva===null) {
            $tiva= 0;
            $tsubtotal= 0;
            $ttotal= 0;
        }else {
            $tiva= $totales[0]->tiva;
            $tsubtotal= $totales[0]->tsubtotal;
            $ttotal= $totales[0]->ttotal;
        }
        $detalles = DB::table('v_detalles_compras')->where('id_compras', $id)->get();
        $compras= DB::select("SELECT * FROM v_compras where id =?", [$id]);
        session()->flash('message', 'Detalle eliminado');
        return view('compras/update',['compras'=>$compras, 'id'=>$id, 'productos'=>$productos->toArray(), 'tiva'=>$tiva,'tsubtotal'=>$tsubtotal,'ttotal'=>$ttotal, 'detalles'=>$detalles]);
    
    }
    public function export(){
        $documento = new Spreadsheet();
        $documento
            ->getProperties()
            ->setCreator("wavp25@gmail.com")
            ->setLastModifiedBy('wavp25@gmail.com')
            ->setTitle('Compras')
            ->setSubject('Compras')
            ->setDescription('')
            ->setKeywords('')
            ->setCategory('');

        $hoja = $documento->getActiveSheet();

        //nombre de la hoja
        $hoja->setTitle("Compras");

        //encabezados
        $hoja->setCellValueByColumnAndRow(1, 1, "ID");
        $hoja->setCellValueByColumnAndRow(2, 1, "FECHA");
        $hoja->setCellValueByColumnAndRow(3, 1, "PROVEEDOR");
        $hoja->setCellValueByColumnAndRow(4, 1, "TIPO DE PAGO");
        $hoja->setCellValueByColumnAndRow(5, 1, "IVA");
        $hoja->setCellValueByColumnAndRow(6, 1, "SUBTOTAL");
        $hoja->setCellValueByColumnAndRow(7, 1, "TOTAL");

        //estilo a encabezados
        $documento->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(true);
        $documento->getActiveSheet()->getStyle('A1:G1')->getAlignment()->setHorizontal('center');

        //consultar en base de datos segun filtros
        $compras = DB::table('v_compras')->get();

   
        
        for ($i = 0; $i < count($compras); $i++) {
  
            //mostrar información de los bienes filtrados en la celdas
            $hoja->setCellValue("A" . $i+2, $compras[$i]->id);
            $hoja->setCellValue("B" . $i+2, $compras[$i]->fecha);
            $hoja->setCellValue("C" . $i+2, $compras[$i]->nombre);
            $hoja->setCellValue("D" . $i+2, $compras[$i]->tipo);
            $hoja->setCellValue("E" . $i+2, $compras[$i]->total_iva);
            $hoja->setCellValue("F" . $i+2, $compras[$i]->subtotal);
            $hoja->setCellValue("G" . $i+2, $compras[$i]->total);
        }
        
        //ajustar tamaño al conteenido de la celda
        $documento->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $documento->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $documento->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $documento->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $documento->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $documento->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $documento->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);

        $hoy = date("Y-m-d");
        $nombreDelDocumento = "$hoy-compras.xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $nombreDelDocumento . '"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($documento, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    public function exportdetalles(){
        $documento = new Spreadsheet();
        $documento
            ->getProperties()
            ->setCreator("wavp25@gmail.com")
            ->setLastModifiedBy('wavp25@gmail.com')
            ->setTitle('detallesCompras')
            ->setSubject('detallesCompras')
            ->setDescription('')
            ->setKeywords('')
            ->setCategory('');

        $hoja = $documento->getActiveSheet();

        //nombre de la hoja
        $hoja->setTitle("detallesCompras");

        //encabezados
        $hoja->setCellValueByColumnAndRow(1, 1, "ID");
        $hoja->setCellValueByColumnAndRow(2, 1, "ID COMPRAS");
        $hoja->setCellValueByColumnAndRow(3, 1, "PRODUCTO");
        $hoja->setCellValueByColumnAndRow(4, 1, "COSTO");
        $hoja->setCellValueByColumnAndRow(5, 1, "CANTDAD");
        $hoja->setCellValueByColumnAndRow(6, 1, "IVA");
        $hoja->setCellValueByColumnAndRow(7, 1, "SUBTOTAL");

        //estilo a encabezados
        $documento->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(true);
        $documento->getActiveSheet()->getStyle('A1:G1')->getAlignment()->setHorizontal('center');

        //consultar en base de datos segun filtros
        $dcompras = DB::table('v_detalles_compras')->get();

   
        
        for ($i = 0; $i < count($dcompras); $i++) {
  
            //mostrar información de los bienes filtrados en la celdas
            $hoja->setCellValue("A" . $i+2, $dcompras[$i]->id);
            $hoja->setCellValue("B" . $i+2, $dcompras[$i]->id_compras);
            $hoja->setCellValue("C" . $i+2, $dcompras[$i]->nombre);
            $hoja->setCellValue("D" . $i+2, $dcompras[$i]->costo);
            $hoja->setCellValue("E" . $i+2, $dcompras[$i]->cantidad);
            $hoja->setCellValue("F" . $i+2, $dcompras[$i]->iva);
            $hoja->setCellValue("G" . $i+2, $dcompras[$i]->subtotal);
        }
        
        //ajustar tamaño al conteenido de la celda
        $documento->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $documento->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $documento->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $documento->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $documento->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $documento->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $documento->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);

        $hoy = date("Y-m-d");
        $nombreDelDocumento = "$hoy-detalles-compras.xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $nombreDelDocumento . '"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($documento, 'Xlsx');
        $writer->save('php://output');
        exit;
    }
}
