<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ProductoController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    
    // cabecera de las tablas
    public function header() {
        $header = DB::select("SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'productos'");
        //dd($header);
        return $header;
    }

    public function create() {
        try {
  
            $header = $this->header();
            return view('/productos/create', ['header'=>$header]);
  
        } catch (\Throwable $th) {
            session()->flash('message', "Error al solicitar ingresar productos nuevos"); 
            session()->flash('alert-class', 'alert-danger');
            return $this->show();
        }
    }

    public function store(Request $request) {  
        try {
            //  $i inicia en 1 para omitir el valor del token 
            $valores= array_values($request->all());
            for ($i=1; $i < count($valores); $i++) { 
                $validar[$i]= $valores[$i];
            }
            //validacion de campos vacions 
            $condicion = true;
            foreach ($validar as $key => $value) {
                if ($value === null) {
                    $condicion = null;
                }
            }
            if ( $condicion === null) {
                session()->flash('message_create', "No se aceptan datos vacios");
                session()->flash('alert-class', 'alert-danger');
                return $this->create();
            }else {
               DB::table('productos')->insert([
                    'codigo' => $request->get('codigo'),
                    'nombre' => $request->get('nombre'),
                    'costo' => $request->get('costo'),
                    'porcentage_ganancia' => $request->get('porcentage_ganancia'),
                    'precio_venta' => $request->get('precio_venta'),
                    'gravable' => $request->get('gravable'),
                    'tipo' => 'unitario',
                    'estatus' => 'activo',
                    'foto' => 'https://www.ecvegasoft.com.mx/images/product/tienda/sinfoto.png',
                ]);
                session()->flash('message', "Nuevos datos ingresados con exito"); 
                session()->flash('alert-class', 'alert-success');
                return $this->show();
            }
        } catch (\Throwable $th) {
            session()->flash('message', "Error al ingresar un producto nuevo"); 
            session()->flash('alert-class', 'alert-danger');
            return $this->show();
        }
    }

    public function show() {
                $header = $this->header();
                //dd($database);
                $datos=[];
                for ($i=0; $i < count($header); $i++) { 
                    $datos[$i]= $header[$i]->COLUMN_NAME;
                }
                $campos = implode(", ",$datos);
    
                $body= DB::table('productos')->paginate(2000);
                $max = count($body);
                if ( $max > 1999 ) {
                    session()->flash('message', "La tabla a mostrar tiene mas de 2000 registros, supera el limite  de espera y visualización, si desea datos especificos puede usar una consulta Sql, o administrarla directamente desde MySql"); 
                    session()->flash('alert-class', 'alert-danger');
                }
                return view('productos.show', ['campos'=>$campos, 'header'=>$header, 'body'=> $body]);
    }

    public function edit(Request $request) {
        if ($request->get('tipo') === 'unitario') {
            try {
                $id = $request->get('id');
                $header = $this->header();
                $body= DB::select("SELECT * FROM productos where id = ?", [$id]);
                return view('/productos/edit', ['header'=>$header, 'body'=> $body, 'id'=>$id]);
    
            } catch (\Throwable $th) {
                session()->flash('message', "No se puede editar el objeto $id de la tabla Productos"); 
                session()->flash('alert-class', 'alert-danger');
                return $this->show();
            }
        } else {
            $id = $request->get('id');
            return app(CompuestoController::class)->editcompuesto($id);
        }
        
    }

    public function update(Request $request) {
        try {
            $id = $request->get('id');
            $atributos = array_keys($request->all());
            //  $i inicia en  27 para omitir el valor del token, method, nombre_tabla e  id
            for ($i=2; $i < count($atributos); $i++) { 
                $campos[$i]= $atributos[$i];
            }
            $valores= array_values($request->all());
            for ($i=2; $i < count($valores); $i++) { 
                $datos[$i]= $valores[$i];
                $validar[$i]= $valores[$i];
            }
            foreach ($datos as $key => $value) {
                $insertar[$key]=  $campos[$key].' = '.'"'.$value.'"';    
            }
            // Validaciones  Requiered
            $condicion = true;
            foreach ($validar as $key => $value) {
                if ($value === null) {
                    $condicion = null;
                }
            }
            if ( $condicion === null) {
                session()->flash('message_create', "No se aceptan datos vacios");
                session()->flash('alert-class', 'alert-danger');
                return $this->edit( $request );
            }else {
                $insertar= implode(",",$insertar);
                DB::update("update productos set $insertar where id = ?", [$id]);
                session()->flash('message', "Edición del objeto $id con exito"); 
                session()->flash('alert-class', 'alert-success');
                return $this->show();
            }
            //dd($insertar);
        } catch (\Throwable $th) {
            session()->flash('message', "No se logró editar el objeto $id de la tabla Productos, se produjo el error $th"); 
            session()->flash('alert-class', 'alert-danger');
            return $this->show();
        }
    }

     // para cambiar un estutus de "eliminado" en un campo establecido ene la tabla
    public function destroy(Request $request) {
        try {
            $id = $request->get('id');
            DB::update("update productos set estatus = 'eliminado' where id  = ?", [$id]);
            session()->flash('message', "Eliminado el objeto $id de la tabla Productos"); 
            session()->flash('alert-class', 'alert-info');
            return $this->show( $request );
        } catch (\Throwable $th) {
            session()->flash('message', "No se logró eliminar el objeto $id de la tabla Productos, es probable que no posea el campo 'estatus'"); 
            session()->flash('alert-class', 'alert-danger');
            return $this->show( $request );
        }
    }

    public function consulta(Request $request) {  
        if ($consulta = $request->get('consulta') != null) {
            try {
                $database = $this->database();
                $nombre = $request->get('nombre_tabla');
                $consulta = $request->get('consulta');
                $body= DB::select($consulta);
                if (count($body) > 0 ) {
                    $campos = $this->header($nombre, $database);
                    for ($i=0; $i < count($campos); $i++) { 
                        $datos[$i]= $campos[$i]->COLUMN_NAME;
                    }
                    $campos = implode(", ",$datos);
        
                    $header = array_keys(get_object_vars($body[0]));
                    $tablas= $this->lista($database);
                    session()->flash('message', "Consulta exitosa"); 
                    session()->flash('alert-class', 'alert-success');
                    return view('tablas.consulta', [ 'campos'=>$campos, 'header'=>$header, 'body'=> $body, 'tablas'=>$tablas, 'nombre'=> $nombre, 'database'=> $database]);
         
                } else {
                    session()->flash('message', "Consulta no genera resultados"); 
                    session()->flash('alert-class', 'alert-info');
                    return $this->show( $request );
                } 
            } catch (\Throwable $th) {
                session()->flash('message', "Error al obtener en la consulta de tabla $nombre, se produjo el error $th"); 
                session()->flash('alert-class', 'alert-danger');
                return $this->show( $request );
            }
        } else {
            session()->flash('message', "La consulta no debe estar vacía"); 
            session()->flash('alert-class', 'alert-danger');
            return $this->show( $request );
        }
    }
    public function exportexcel(Request $request){
        $database = $this->database();
        $nombre = $request->get('nombre_tabla');

        $header = $this->header($nombre);
        for ($i=0; $i < count($header); $i++) { 
            $cabecera[$i]= $header[$i]->COLUMN_NAME;
        }
        $documento = new Spreadsheet();
        $documento
            ->getProperties()
            ->setCreator("Alcides Cádiz")
            ->setLastModifiedBy('Alcides Cádiz')
            ->setTitle($nombre)
            ->setSubject($nombre)
            ->setDescription('')
            ->setKeywords('')
            ->setCategory('');
        $hoja = $documento->getActiveSheet();
        //nombre de la hoja
        $hoja->setTitle($nombre);
        //encabezados
        for ($i = 0; $i < count($cabecera); $i++) {    
            $hoja->setCellValueByColumnAndRow($i+1, 1, $cabecera[$i]);   
        }
        //estilo a encabezados
        $documento->getActiveSheet()->getStyle('A1:Z1')->getFont()->setBold(true);
        $documento->getActiveSheet()->getStyle('A1:Z1')->getAlignment()->setHorizontal('center');
        //datos de la tabla
        $dcompras = DB::table($nombre)->get();
     
        $fila = 2;
        foreach  ($dcompras as $item){
            for ($i = 0; $i < count($cabecera); $i++) {
                $key =$cabecera[$i];
                $hoja->setCellValueByColumnAndRow($i+1, $fila, $item->$key);
            }
            $fila++;
       }
        $columnas =['A','B','C','D','E','F', 'G', 'H', 'I', 'J','K','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA' ];
        for ($i = 0; $i < count($cabecera); $i++) {
            $documento->getActiveSheet()->getColumnDimension($columnas[$i])->setAutoSize(true);
        }
        // nombre para el archivo excel
        $hoy = date("Y-m-d");
        $nombreDelDocumento = "$hoy-$nombre.xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $nombreDelDocumento . '"');
        header('Cache-Control: max-age=0');
        $writer = IOFactory::createWriter($documento, 'Xlsx');
        $writer->save('php://output');
        exit;
    }
}
