<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use vendor\autoload;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class VentaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
        $ventas = DB::table('v_ventas')->paginate(5);
        return view('/ventas/index', ['ventas'=>$ventas]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $this->validate($request, [
            'fecha' => 'required',
            'id_cliente' => 'required',
            'id_tipo_pago' => 'required',
            'total_iva' => 'required|numeric',
            'subtotal' => 'required|numeric',
            'total' => 'required|numeric',
            ]);
    
        Venta::create($request->all());

        session()->flash('message', 'La venta ha sido registrada');
        return redirect('ventas');
    }
    public function detallestore(Request $request)
    {
        $this->validate($request, [
            'id_productos' => 'required',
            'precio_venta' => 'required|numeric',
            'cantidad' => 'required|numeric',
            'subtotal' => 'required|numeric',
        ]);
        $id_detalle = $request->get('id_productos');
        //dd($id_detalle);
        $busca = DB::select("select id, gravable, costo from productos where nombre= '$id_detalle'");
        $busca[0]->id;
        $busca[0]->gravable;
        $busca[0]->costo;
        if ($busca[0]->gravable === 'si') {
            $monto_iva =$request->get('precio_venta')*$request->get('cantidad') * 0.16;
        }else {
            $monto_iva = 0;
        }
    
        DetalleVenta::create([
            'id_productos'=>$busca[0]->id,
            'costo'=>$busca[0]->costo,
            'precio_venta' => $request->get('precio_venta'),
            'cantidad' => $request->get('cantidad'),
            'iva' => $monto_iva,
            'subtotal' => $request->get('subtotal'),
            'id_ventas' => $request->get('id_ventas')
        ]);
        return redirect('/facturav');
    }
    public function detallesedit(Request $request)
    {
        if ($request->get('id_productos')=== null || $request->get('precio_venta')=== null || $request->get('cantidad')=== null || $request->get('subtotal')=== null){
            session()->flash('message', 'No hay detalles que registrar');
        } else {
            $this->validate($request, [
                'id_productos' => 'required',
                'precio_venta' => 'required|numeric',
                'cantidad' => 'required|numeric',
                'subtotal' => 'required|numeric',
            ]);
            $id_detalle = $request->get('id_productos');
            //dd($id_detalle);
            $busca = DB::select("select id, gravable, costo from productos where nombre= '$id_detalle'");
            $busca[0]->id;
            $busca[0]->gravable;
            if ($busca[0]->gravable === 'si') {
                $monto_iva =$request->get('precio_venta')*$request->get('cantidad') * 0.16;
            }else {
                $monto_iva = 0;
            }
        
            DetalleVenta::create([
                'id_productos'=>$busca[0]->id,
                'costo'=>$busca[0]->costo,
                'precio_venta' => $request->get('precio_venta'),
                'cantidad' => $request->get('cantidad'),
                'iva' => $monto_iva,
                'subtotal' => $request->get('subtotal'),
                'id_ventas' => $request->get('id_ventas')
            ]);
            session()->flash('message', 'Detalle registrado');
        }
            // factura 
            // coodigo proveedores productos y tipo de pago:
            $id= $request->get('id_ventas');
            $productos = DB::table('productos')->get();  
            $totales= DB::select("select sum(iva) as tiva,sum(subtotal) as tsubtotal, (sum(iva)+sum(subtotal))as ttotal from detalle_ventas where id_ventas = '$id'");
            if ($totales[0]->tiva===null) {
                $tiva= 0;
                $tsubtotal= 0;
                $ttotal= 0;
            }else {
                $tiva= $totales[0]->tiva;
                $tsubtotal= $totales[0]->tsubtotal;
                $ttotal= $totales[0]->ttotal;
            }
            $detalles= DB::select("SELECT d.id, d.id_ventas, p.nombre, d.precio_venta, d.cantidad, d.iva, d.subtotal, d.subtotal, d.estatus  
                        FROM detalle_ventas d
                        INNER join productos p on d.id_productos = p.id
                        where d.id_ventas = '$id' and d.estatus='activo'");
    
            $ventas= DB::select("SELECT * FROM v_ventas where id = '$id'");
            return view('ventas/update',['ventas'=>$ventas, 'id'=>$id, 'productos'=>$productos->toArray(),'tiva'=>$tiva,'tsubtotal'=>$tsubtotal,'ttotal'=>$ttotal, 'detalles'=>$detalles]);
        
        
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function show(Venta $venta) 
    {
        // factura 
        // coodigo proveedores productos y tipo de pago:
        $codigo= DB::select("SELECT `AUTO_INCREMENT` as codigo FROM  INFORMATION_SCHEMA.TABLES
        WHERE TABLE_SCHEMA = 'appsialaravel'
        AND   TABLE_NAME   = 'ventas'");
        if (count($codigo)) {
            $idnew = $codigo[0]->codigo;
        }else {
            $idnew=1;
        }
        $totales= DB::select("select sum(iva) as tiva,sum(subtotal) as tsubtotal, (sum(iva)+sum(subtotal))as ttotal from detalle_ventas where id_ventas = '$idnew'");
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
        $ventas = DB::table('ventas')
                    ->where('estatus', 'activo')
                    ->get();
        $detalles= DB::select("SELECT d.id, d.id_ventas, p.nombre, d.precio_venta, d.cantidad, d.iva, d.subtotal, d.subtotal, d.estatus  
        FROM detalle_ventas d
        INNER join productos p on d.id_productos = p.id
        where d.id_ventas = '$idnew' and d.estatus='activo'");

        return view('/ventas/create', [ 'productos'=>$productos->toArray(),'ventas'=>$ventas->toArray(),'idnew'=>$idnew, 'detalles'=>$detalles, 'tiva'=>$tiva,'tsubtotal'=>$tsubtotal,'ttotal'=>$ttotal]);
    }

    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Compra  $compra
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    { 
        $productos = DB::table('productos')->get();  
        $totales= DB::select("select sum(iva) as tiva,sum(subtotal) as tsubtotal, (sum(iva)+sum(subtotal))as ttotal from detalle_ventas where id_ventas = '$id'");
        if ($totales[0]->tiva===null) {
            $tiva= 0;
            $tsubtotal= 0;
            $ttotal= 0;
        }else {
            $tiva= $totales[0]->tiva;
            $tsubtotal= $totales[0]->tsubtotal;
            $ttotal= $totales[0]->ttotal;
        }
        $detalles= DB::select("SELECT d.id, d.id_ventas, p.nombre, d.precio_venta, d.cantidad, d.iva, d.subtotal, d.subtotal, d.estatus  
                    FROM detalle_ventas d
                    INNER join productos p on d.id_productos = p.id
                    where d.id_ventas = '$id' and d.estatus='activo'");

        $ventas= DB::select("SELECT * FROM v_ventas where id = '$id'");

        return view('ventas/update',['ventas'=>$ventas, 'id'=>$id, 'productos'=>$productos->toArray(),'tiva'=>$tiva,'tsubtotal'=>$tsubtotal,'ttotal'=>$ttotal, 'detalles'=>$detalles]);
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
        if ($request->get('fecha')=== null) {
            session()->flash('message', 'Verifique que todos los datos esten completos');
            $productos = DB::table('productos')->get();  
            $id=$request->get('id');
            $totales= DB::select("select sum(iva) as tiva,sum(subtotal) as tsubtotal, (sum(iva)+sum(subtotal))as ttotal from detalle_ventas where id_ventas = '$id'");
            if ($totales[0]->tiva===null) {
                $tiva= 0;
                $tsubtotal= 0;
                $ttotal= 0;
            }else {
                $tiva= $totales[0]->tiva;
                $tsubtotal= $totales[0]->tsubtotal;
                $ttotal= $totales[0]->ttotal;
            }
            $detalles= DB::select("SELECT d.id, d.id_ventas, p.nombre, d.precio_venta, d.cantidad, d.iva, d.subtotal, d.subtotal, d.estatus  
                        FROM detalle_ventas d
                        INNER join productos p on d.id_productos = p.id
                        where d.id_ventas = '$id' and d.estatus='activo'");

            $ventas= DB::select("SELECT * FROM v_ventas where id ='$id'");

            return view('ventas/update',['ventas'=>$ventas, 'id'=>$id, 'productos'=>$productos->toArray(),'tiva'=>$tiva,'tsubtotal'=>$tsubtotal,'ttotal'=>$ttotal, 'detalles'=>$detalles]);
        } else {
            $this->validate($request, [
                'fecha' => 'required',
                'id_cliente' => 'required',
                'id_tipo_pago' => 'required',
                'total_iva' => 'required|numeric',
                'subtotal' => 'required|numeric',
                'total' => 'required|numeric',
            ]);
            DB::table('ventas')
                ->where('id', $request->get('id'))
                ->update([
                    'fecha' => $request->get('fecha'),
                    'id_cliente' => $request->get('id_cliente'),
                    'id_tipo_pago' => $request->get('id_tipo_pago'),
                    'total_iva' => $request->get('total_iva'),
                    'subtotal' => $request->get('subtotal'),
                    'total' => $request->get('total'),
                ]);
            return redirect('ventas');
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function venta_destroy($id)
    {
        DB::table('ventas')
            ->where('id', $id)
            ->update([
                'estatus' => 'eliminado',
            ]);
        DB::table('detalle_ventas')
            ->where('id_ventas', $id)
            ->update([
                'estatus' => 'eliminado',
            ]);
        session()->flash('message', 'La venta ha sido eliminda');
        return redirect('/ventas');
    }
    public function detalledestroy($id)
    {
        DB::table('detalle_ventas')->delete($id);
        return redirect('/facturav');
    }
    public function detalledestroyedit($id)
    {
        $id_ventas = DB::select("SELECT id, id_ventas FROM detalle_ventas where id = '$id'");
        $id_ventas= $id_ventas[0]->id_ventas;
        DB::table('detalle_ventas')->delete($id);
       // factura 
        // coodigo proveedores productos y tipo de pago: 
        $productos = DB::table('productos')->get();  
        $totales= DB::select("select sum(iva) as tiva,sum(subtotal) as tsubtotal, (sum(iva)+sum(subtotal))as ttotal from detalle_ventas where id_ventas = '$id_ventas'");
        if ($totales[0]->tiva===null) {
            $tiva= 0;
            $tsubtotal= 0;
            $ttotal= 0;
        }else {
            $tiva= $totales[0]->tiva;
            $tsubtotal= $totales[0]->tsubtotal;
            $ttotal= $totales[0]->ttotal;
        }
        $detalles= DB::select("SELECT d.id, d.id_ventas, p.nombre, d.precio_venta, d.cantidad, d.iva, d.subtotal, d.subtotal, d.estatus  
                    FROM detalle_ventas d
                    INNER join productos p on d.id_productos = p.id
                    where d.id_ventas = '$id_ventas' and d.estatus='activo'");

        $ventas= DB::select("SELECT * FROM v_ventas where id = '$id_ventas'");

        return view('ventas/update',['ventas'=>$ventas, 'id'=>$id_ventas, 'productos'=>$productos->toArray(), 'tiva'=>$tiva,'tsubtotal'=>$tsubtotal,'ttotal'=>$ttotal, 'detalles'=>$detalles]);
    }

    public function exportv(){
        $documento = new Spreadsheet();
        $documento
            ->getProperties()
            ->setCreator("wavp25@gmail.com")
            ->setLastModifiedBy('wavp25@gmail.com')
            ->setTitle('Ventas')
            ->setSubject('Ventas')
            ->setDescription('')
            ->setKeywords('')
            ->setCategory('');

        $hoja = $documento->getActiveSheet();

        //nombre de la hoja
        $hoja->setTitle("Ventas");

        //encabezados
        $hoja->setCellValueByColumnAndRow(1, 1, "ID");
        $hoja->setCellValueByColumnAndRow(2, 1, "FECHA");
        $hoja->setCellValueByColumnAndRow(3, 1, "CLIENTES");
        $hoja->setCellValueByColumnAndRow(4, 1, "TIPO DE PAGO");
        $hoja->setCellValueByColumnAndRow(5, 1, "IVA");
        $hoja->setCellValueByColumnAndRow(6, 1, "SUBTOTAL");
        $hoja->setCellValueByColumnAndRow(7, 1, "TOTAL");

        //estilo a encabezados
        $documento->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(true);
        $documento->getActiveSheet()->getStyle('A1:G1')->getAlignment()->setHorizontal('center');

        //consultar en base de datos segun filtros
        $ventas = DB::table('v_ventas')->get();

   
        
        for ($i = 0; $i < count($ventas); $i++) {
  
            //mostrar información de los bienes filtrados en la celdas
            $hoja->setCellValue("A" . $i+2, $ventas[$i]->id);
            $hoja->setCellValue("B" . $i+2, $ventas[$i]->fecha);
            $hoja->setCellValue("C" . $i+2, $ventas[$i]->nombre);
            $hoja->setCellValue("D" . $i+2, $ventas[$i]->tipo);
            $hoja->setCellValue("E" . $i+2, $ventas[$i]->total_iva);
            $hoja->setCellValue("F" . $i+2, $ventas[$i]->subtotal);
            $hoja->setCellValue("G" . $i+2, $ventas[$i]->total);
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
        $nombreDelDocumento = "$hoy-ventas.xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $nombreDelDocumento . '"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($documento, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    public function exportdetallesv(){
        $documento = new Spreadsheet();
        $documento
            ->getProperties()
            ->setCreator("wavp25@gmail.com")
            ->setLastModifiedBy('wavp25@gmail.com')
            ->setTitle('detallesVentas')
            ->setSubject('detallesVentas')
            ->setDescription('')
            ->setKeywords('')
            ->setCategory('');

        $hoja = $documento->getActiveSheet();

        //nombre de la hoja
        $hoja->setTitle("detallesVentas");

        //encabezados
        $hoja->setCellValueByColumnAndRow(1, 1, "ID");
        $hoja->setCellValueByColumnAndRow(2, 1, "ID VENTA");
        $hoja->setCellValueByColumnAndRow(3, 1, "PRODUCTO");
        $hoja->setCellValueByColumnAndRow(4, 1, "PRECIO DE VENTA");
        $hoja->setCellValueByColumnAndRow(5, 1, "CANTDAD");
        $hoja->setCellValueByColumnAndRow(6, 1, "IVA");
        $hoja->setCellValueByColumnAndRow(7, 1, "SUBTOTAL");

        //estilo a encabezados
        $documento->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(true);
        $documento->getActiveSheet()->getStyle('A1:G1')->getAlignment()->setHorizontal('center');

        //consultar en base de datos segun filtros
        $dventas = DB::table('v_detalles_ventas')->get();

   
        
        for ($i = 0; $i < count($dventas); $i++) {
  
            //mostrar información de los bienes filtrados en la celdas
            $hoja->setCellValue("A" . $i+2, $dventas[$i]->id);
            $hoja->setCellValue("B" . $i+2, $dventas[$i]->id_ventas);
            $hoja->setCellValue("C" . $i+2, $dventas[$i]->nombre);
            $hoja->setCellValue("D" . $i+2, $dventas[$i]->precio_venta);
            $hoja->setCellValue("E" . $i+2, $dventas[$i]->cantidad);
            $hoja->setCellValue("F" . $i+2, $dventas[$i]->iva);
            $hoja->setCellValue("G" . $i+2, $dventas[$i]->subtotal);
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
        $nombreDelDocumento = "$hoy-detalles-ventas.xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $nombreDelDocumento . '"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($documento, 'Xlsx');
        $writer->save('php://output');
        exit;
    }
}
