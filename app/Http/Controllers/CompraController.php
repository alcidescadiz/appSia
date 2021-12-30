<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreComprasRequest;
use App\Models\Compra;
use App\Models\DetalleCompra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CompraController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    
    public function index(){
        return view('/compras/index', ['compras'=>DB::table('v_compras')->paginate(15)]);
    }
    public function store(StoreComprasRequest $request){
        
        Compra::create([
                'fecha' => $request->get('fecha'),
                'proveedore_id' => $request->get('proveedore_id'),
                'tipospago_id' => $request->get('tipospago_id'),
                'total_iva' => $request->get('total_iva'),
                'subtotal' => $request->get('subtotal'),
                'total' => $request->get('total'),
                'estatus' => 'activo'
        ]);
            DB::table('cuentas')->insert([
                'codigo' => $request->get('id'),
                'tipo' => 'compras',
                'fecha_pago' => $request->get('fecha'),
                'estatus' => 'pendiente',
                'tipospago_id' => $request->get('tipospago_id'),
            ]); 
        session()->flash('message', 'La compra ha sido registrada');
        return redirect('compras');
    }

    public function show(){
        $idnew= Compra::idnew();
        $totales= Compra::totales($idnew);
        $productos = DB::table('productos')->get();  
        $detalles = DB::table('v_detalles_compras')->where('compra_id', $idnew)->get();
        return view('/compras.create', [ 'productos'=>$productos->toArray(), 'idnew'=>$idnew, 'detalles'=>$detalles, 'totales'=>$totales]);
    }
    public function edit($id){
        $productos = DB::table('productos')->get();  
        $totales= Compra::totales($id);
        $detalles = DB::table('v_detalles_compras')->where('compra_id', $id)->get();
        $compras= DB::select("SELECT * FROM v_compras where id =?", [$id]);
        return view('compras/update',['compras'=>$compras, 'id'=>$id, 'productos'=>$productos->toArray(), 'totales'=>$totales, 'detalles'=>$detalles]);
    }
    public function update(Request $request, $id){
            if ($request->get('fecha')=== null || $request->get('proveedore_id')=== null || $request->get('id') <> $id) {
                session()->flash('message', 'Favor incluir todos los datos');
                session()->flash('alert-class', 'alert-danger');
                return $this->edit($id);
            }else {
                Compra::findOrFail($id)
                ->update([
                    'fecha' => $request->get('fecha'),
                    'proveedore_id' => $request->get('proveedore_id'),
                    'tipospago_id' => $request->get('tipospago_id'),
                    'total_iva' => $request->get('total_iva'),
                    'subtotal' => $request->get('subtotal'),
                    'total' => $request->get('total'),
                    'estatus' => 'activo'
                ]);
                DB::table('cuentas')->where('codigo', $id)->where('tipo', 'compras')
                            ->update([
                                'fecha_pago' => $request->get('fecha'),
                                'tipospago_id' => $request->get('tipospago_id'),
                            ]);
                session()->flash('message', 'Compra ha sido editada');
                return redirect('compras');    
            }
    }
    public function destroy($id){  
        DB::table('compras')->where('id', $id)->update(['estatus' => 'eliminado',]);
        DB::table('detalle_compras')->where('compra_id', $id)->update(['estatus' => 'eliminado']);
        DB::table('cuentas')->where('codigo', $id)->where('tipo', 'compras')->update(['estatus' => 'cancelado']);
        session()->flash('message', 'La compra ha sido eliminada');
        session()->flash('alert-class', 'alert-danger');
        return redirect('/compras');
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
            $hoja->setCellValue("B" . $i+2, $dcompras[$i]->compra_id);
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
