<?php

namespace App\Http\Controllers;

use App\Mail\FacturaMail;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

class VentaController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    public function index(){
        $ventas = DB::table('v_ventas')->paginate(15);
        return view('/ventas/index', ['ventas'=>$ventas]);
    }
    public function store(Request $request){
        $this->validate($request, [
            'fecha' => 'required',
            'cliente_id' => 'required',
            'tipospago_id' => 'required',
            'total_iva' => 'required|numeric',
            'subtotal' => 'required|numeric',
            'total' => 'required|numeric',
            ]);
        Venta::create($request->all());
        DB::table('cuentas')->insert([
            'codigo' => $request->get('id'),
            'tipo' => 'ventas',
            'fecha_pago' => $request->get('fecha'),
            'estatus' => 'pendiente',
            'tipospago_id' => $request->get('tipospago_id'),
        ]);
        if( @fsockopen('www.google.com', 80)) {// ¿hay internet?
            $ventas= DB::select("select * from v_ventas where id = ?", [$request->get('id')]);
            $detalles= DB::select("select * from v_detalles_ventas where venta_id = ?", [$request->get('id')]);
            $email_cliente= DB::select("select email from clientes where id = ?", [$request->get('id_cliente')]);
            Mail::to($email_cliente)->send(new FacturaMail($ventas, $detalles));
            session()->flash('message', 'La venta ha sido registrada');
        }else{
            session()->flash('message', 'La venta ha sido registrada, No se ha enviado el email por falla a conexión');
        }
        return redirect('ventas');
    }

    public function show(Venta $venta) // mostar factura
    {
        $idnew= Venta::idnew();
        $totales= Venta::totales($idnew);
        $productos = DB::table('productos')->get();  
        $detalles= DB::table('v_detalles_ventas')->where('venta_id', $idnew)->get();
        return view('/ventas/create', [ 'productos'=>$productos->toArray(),'idnew'=>$idnew, 'detalles'=>$detalles, 'totales'=>$totales]);
    }

    public function edit($id){ 
        $productos = DB::table('productos')->get();  
        $totales= Venta::totales($id);
        $detalles= DB::table('v_detalles_ventas')->where('venta_id', $id)->get();
        $ventas= DB::select("SELECT * FROM v_ventas where id = ?", [$id]);
        return view('ventas/update',['ventas'=>$ventas, 'id'=>$id, 'productos'=>$productos->toArray(),'totales'=>$totales, 'detalles'=>$detalles]);
    }
    public function update(Request $request){
        if ($request->get('fecha')=== null) {
            session()->flash('message', 'Verifique que todos los datos esten completos');
            $id=$request->get('id');
            return $this->edit($id);
        } else {
            $this->validate($request, [
                'fecha' => 'required',
                'cliente_id' => 'required',
                'tipospago_id' => 'required',
                'total_iva' => 'required|numeric',
                'subtotal' => 'required|numeric',
                'total' => 'required|numeric',
            ]);
            DB::table('ventas')
                ->where('id', $request->get('id'))
                ->update([
                    'fecha' => $request->get('fecha'),
                    'cliente_id' => $request->get('cliente_id'),
                    'tipospago_id' => $request->get('tipospago_id'),
                    'total_iva' => $request->get('total_iva'),
                    'subtotal' => $request->get('subtotal'),
                    'total' => $request->get('total'),
                ]);
            DB::table('cuentas')->where('codigo',$request->get('id'))->where('tipo', 'ventas')
                ->update([
                    'fecha_pago' => $request->get('fecha'),
                    'tipospago_id' => $request->get('tipospago_id'),
            ]);
            session()->flash('message', 'La venta ha sido editada');
            return redirect('ventas');
        }
        
    }
    public function venta_destroy($id){
        DB::table('ventas')->where('id', $id)->update(['estatus' => 'eliminado',]);
        DB::table('detalle_ventas')->where('venta_id', $id)->update(['estatus' => 'eliminado',]);
        DB::table('cuentas')->where('codigo', $id)->where('tipo', 'ventas') ->update(['estatus' => 'cancelado']);
        session()->flash('message', 'La venta ha sido eliminada');
        return redirect('/ventas');
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
            $hoja->setCellValue("B" . $i+2, $dventas[$i]->venta_id);
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
