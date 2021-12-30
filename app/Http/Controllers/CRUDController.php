<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CRUDController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    
    // Lista de la base de la datos
    /*public function database() {
        $tablas= DB::select("SELECT DISTINCT TABLE_SCHEMA FROM INFORMATION_SCHEMA.COLUMNS ORDER BY COLUMNS.TABLE_SCHEMA ASC");
        $atributos = array_values($tablas);
        for ($i=0; $i < count( array_values($tablas)); $i++) { 
            $database[$i]= $atributos[$i]->TABLE_SCHEMA;
        }
        return $database;
    }*/
    //  en vez de listar todas las bases de datos, puede insertar las BD que desea administrar
    public function database() {
            $database =  'appsialaravel';
        return $database;
    }

    // lista de todas las tablas de la base de datos
    /*public function lista($database) {
        $tablas= DB::select("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '$database'");
        $atributos = array_values($tablas);
        for ($i=0; $i < count( array_values($tablas)); $i++) { 
            $lista[$i]= $atributos[$i]->TABLE_NAME;
        }
        return $lista;
    }*/
    //  en vez de listar todas las tablas, puede insertar las tablas que desea administrar
    public function lista() {
        $lista =  ['clientes', 'proveedores', 'tipospagos'];
        return $lista;
    }
    
    // cabecera de las tablas
    public function header($nombre) {
        $header = DB::select("SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$nombre'");
        //dd($header);
        return $header;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //obtenere base de datos
    /*public function getDB() {
        $database= $this->database();
        return view('tablas.indexDB', ['database'=> $database]);
    }*/

    public function index() {
            $database= 'appsialaravel';
            $tablas= $this->lista($database);
            return view('tablas.index', ['tablas'=>$tablas, 'database'=> $database]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        try {
            $database = $this->database();
            $nombre = $request->get('nombre_tabla');
            $tablas= $this->lista($database);
            $header = $this->header($nombre);
            return view('/tablas/create', ['header'=>$header, 'tablas'=>$tablas,'nombre'=> $nombre, 'database'=> $database]);
  
        } catch (\Throwable $th) {
            session()->flash('message', "Error al solicitar ingresar datos nuevos datos a la tabla $nombre"); 
            session()->flash('alert-class', 'alert-danger');
            return $this->show( $request );
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {  
        try {
            $nombre =$request->get('nombre_tabla'); 
            $database = $this->database();
            $atributos = array_keys($request->all());
            //  $i inicia en 3 para omitir el valor del token , nombre_tabla y nombre_db
            for ($i=3; $i < count($atributos); $i++) { 
                $campos[$i]= $atributos[$i];
            }
            $valores= array_values($request->all());
            for ($i=3; $i < count($valores); $i++) { 
                $datos[$i]= '"'.$valores[$i].'"';
                $validar[$i]= $valores[$i];
            }
            for ($i=0; $i < count($campos); $i++) { 
                $incognitas[$i]= '?';
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
                    return $this->create( $request );
                }else {
                    $campos= implode(",", $campos);
                    $incognitas= implode(",", $incognitas);
                    $datos = implode(",", $datos);
                    
                    DB::insert("insert into $database.$nombre ($campos) values ($datos)");
                    session()->flash('message', "Nuevos datos ingresados con exito"); 
                    session()->flash('alert-class', 'alert-success');
                    return $this->show( $request );
                }
            
        } catch (\Throwable $th) {
            session()->flash('message', "Error al solicitar ingresar datos nuevos datos a la tabla $nombre"); 
            session()->flash('alert-class', 'alert-danger');
            return $this->show( $request );
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request) {
 
            try {
                $database = $this->database();
                $nombre = $request->get('nombre_tabla');
                $header = $this->header($nombre);
                //dd($database);
                $datos=[];
                for ($i=0; $i < count($header); $i++) { 
                    $datos[$i]= $header[$i]->COLUMN_NAME;
                }
                $campos = implode(", ",$datos);
    
                $tablas= $this->lista($database);
                $body= DB::table($nombre)->paginate(2000);
                $max = count($body);
                if ( $max > 1999 ) {
                    session()->flash('message', "La tabla a mostrar tiene mas de 2000 registros, supera el limite  de espera y visualización, si desea datos especificos puede usar una consulta Sql, o administrarla directamente desde MySql"); 
                    session()->flash('alert-class', 'alert-danger');
                }
                return view('tablas.show', [  'campos'=>$campos, 'header'=>$header, 'body'=> $body, 'tablas'=>$tablas, 'nombre'=> $nombre, 'database'=> $database]);
            } catch (\Throwable $th) {
                session()->flash('message_tabla', "Error al obtener la tabla $nombre, se produjo el error $th"); 
                session()->flash('alert-class', 'alert-danger');
                return $this->index( $request );
            }
      
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request) {
        try {
            $id = $request->get('id');
            $key_id = $request->get('key_id');
            $nombre = $request->get('nombre_tabla');
            $database = $this->database();
            $header = $this->header($nombre);
            $tablas= $this->lista($database);
            $body= DB::select("SELECT * FROM $nombre where $key_id = ?", [$id]);
            return view('/tablas/edit', ['header'=>$header, 'body'=> $body,'tablas'=>$tablas,'nombre'=> $nombre, 'id'=>$id, 'database'=> $database]);

        } catch (\Throwable $th) {
            session()->flash('message', "No se puede editar el objeto $id de la tabla $nombre"); 
            session()->flash('alert-class', 'alert-danger');
            return $this->show( $request );
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request) {
        try {
            $nombre = $request->get('nombre_tabla');
            $database = $this->database();
            $key_id = $request->get('key_id');
            $value_id = $request->get('value_id');
    
            $atributos = array_keys($request->all());
            //  $i inicia en  7 para omitir el valor del token, method, nombre_tabla e  id
            for ($i=7; $i < count($atributos)-1; $i++) { 
                $campos[$i]= $atributos[$i];
            }
            $valores= array_values($request->all());
            for ($i=7; $i < count($valores)-1; $i++) { 
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
                    DB::update("update $nombre set $insertar where $key_id = ?", [$value_id]);
                    session()->flash('message', "Edición exitosa"); 
                    session()->flash('alert-class', 'alert-success');
                    return $this->show( $request );
                }

        } catch (\Throwable $th) {
            session()->flash('message', "No se logró editar el objeto $value_id de la tabla $nombre, se produjo el error $th"); 
            session()->flash('alert-class', 'alert-danger');
            return $this->show( $request );
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     // para cambiar un estutus de "eliminado" en un campo establecido ene la tabla
    public function destroy(Request $request) {
        try {
            $nombre = $request->get('nombre_tabla');
            $database = $this->database();
            $key_id = $request->get('key_id');
            $id = $request->get('id');
            DB::update("update $nombre set estatus = 'eliminado' where $key_id  = ?", [$id]);
            session()->flash('message', "Eliminado el objeto $id de la tabla $nombre"); 
            session()->flash('alert-class', 'alert-info');
            return $this->show( $request );
        } catch (\Throwable $th) {
            session()->flash('message', "No se logró eliminar el objeto $id de la tabla $nombre, es probable que no posea el campo 'estatus'"); 
            session()->flash('alert-class', 'alert-danger');
            return $this->show( $request );
        }
    }
    // Para borrar definitivo
    /*public function destroy(Request $request) {
        try {
            $nombre = $request->get('nombre_tabla');
            $database = $this->database();
            $key_id = $request->get('key_id');
            $id = $request->get('id');
            DB::delete("delete from $nombre where $key_id = ?", [$id]);
            session()->flash('message', "Eliminado el objeto $id de la tabla $nombre"); 
            session()->flash('alert
            return $this->show( $request );
        } catch (\Throwable $th) {
            session()->flash('message', "No se logró eliminar el objeto $id de la tabla $nombre"); 
            session()->flash('alert-class', 'alert-danger');
            return $this->show( $request );
        }
    }*/
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
