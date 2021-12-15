<?php


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/offline', function () {
    return view('vendor/laravelpwa/offline');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// compras
Route::get('/compras', [App\Http\Controllers\CompraController::class, 'index'])->name('compras');
Route::get('/compras.create', [App\Http\Controllers\CompraController::class, 'show']);
Route::post('/compras', [App\Http\Controllers\CompraController::class, 'store']);
Route::delete('compras/{id}/edit', [App\Http\Controllers\CompraController::class, 'compra_destroy']);
Route::put('compras/{id}',  [App\Http\Controllers\CompraController::class, 'edit'])->name('compras.edit');
Route::put('comprase/{id}/edit',  [App\Http\Controllers\CompraController::class, 'update']);
// detalles compras
Route::post('/detalles', [App\Http\Controllers\CompraController::class, 'detallestore']);
Route::delete('detalles/{id}', [App\Http\Controllers\CompraController::class, 'detalledestroy']);
Route::post('/detallesedit', [App\Http\Controllers\CompraController::class, 'detallesedit']);
Route::delete('detallesedit/{id}', [App\Http\Controllers\CompraController::class, 'detalledestroyedit']);
//export excel
Route::post('/export', [App\Http\Controllers\CompraController::class, 'export']);
Route::post('/exportdetalles', [App\Http\Controllers\CompraController::class, 'exportdetalles']);

Route::post('/exportv', [App\Http\Controllers\VentaController::class, 'exportv']);
Route::post('/exportdetallesv', [App\Http\Controllers\VentaController::class, 'exportdetallesv']);

// ventas
Route::get('/ventas', [App\Http\Controllers\VentaController::class, 'index']);
Route::get('/facturav', [App\Http\Controllers\VentaController::class, 'show']);
Route::post('/ventas', [App\Http\Controllers\VentaController::class, 'store']);
Route::delete('ventas/{id}/edit', [App\Http\Controllers\VentaController::class, 'venta_destroy']);
Route::put('ventase/{id}',  [App\Http\Controllers\ventaController::class, 'edit']);
Route::put('ventase/{id}/edit',  [App\Http\Controllers\VentaController::class, 'update']);
// detalles ventas
Route::post('/detallesv', [App\Http\Controllers\VentaController::class, 'detallestore']);
Route::post('/detallesvedit', [App\Http\Controllers\VentaController::class, 'detallesedit']);
Route::delete('detallesv/{id}', [App\Http\Controllers\VentaController::class, 'detalledestroy']);
Route::delete('detallesvedit/{id}', [App\Http\Controllers\VentaController::class, 'detalledestroyedit']);

// Almacen
Route::get('/almacen', [App\Http\Controllers\AlmacenController::class, 'index']);
// indicadores
Route::get('/indicadores/{type?}', [App\Http\Controllers\IndicadorController::class, 'index']);
Route::post('/entrefechas', [App\Http\Controllers\IndicadorController::class, 'entrefechas']);
Route::get('/hoy', [App\Http\Controllers\IndicadorController::class, 'hoy']);
Route::get('/ganancias', [App\Http\Controllers\IndicadorController::class, 'ganancias']);
Route::post('/pordia', [App\Http\Controllers\IndicadorController::class, 'pordia']);
// cuentas
Route::get('/pagar', [App\Http\Controllers\CuentaController::class, 'pagar']);
Route::put('pagar/{id}/edit',  [App\Http\Controllers\CuentaController::class, 'updatepagar']);
Route::get('/cobrar', [App\Http\Controllers\CuentaController::class, 'cobrar']);
Route::put('cobrar/{id}/edit',  [App\Http\Controllers\CuentaController::class, 'updatecobrar']);

// compuestos 
Route::get('/compuestos', [App\Http\Controllers\CompuestoController::class, 'compuestos']);
Route::post('/crearcompuesto', [App\Http\Controllers\CompuestoController::class, 'crearcompuesto']);
Route::put('/editcompuesto/{id}',  [App\Http\Controllers\CompuestoController::class, 'editcompuesto']);
Route::PUT('updatecompuesto/{id}',  [App\Http\Controllers\CompuestoController::class, 'updatecompuesto']);
Route::post('/detallescompuestos', [App\Http\Controllers\CompuestoController::class, 'detallescompuestos']);
Route::delete('detallescompuestos/{id}', [App\Http\Controllers\CompuestoController::class, 'detallecompuestodestroy']);
Route::post('/detallescompuestosedit', [App\Http\Controllers\CompuestoController::class, 'detallescompuestosedit']);
Route::delete('detallescompuestosedit/{id}', [App\Http\Controllers\CompuestoController::class, 'editdetalecompuestodestroy']);

//Route Hooks - Do not delete//
Route::view('tipospagos', 'livewire.tipospagos.index')->middleware('auth');
Route::view('productos', 'livewire.productos.index')->middleware('auth');
Route::view('clientes', 'livewire.clientes.index')->middleware('auth');
Route::view('proveedores', 'livewire.proveedores.index')->middleware('auth');

// imprimir facturas
Route::get("print/{id}", function ($id) {

		$ventas= DB::select("select * from v_ventas where id = '$id'");
		$detalles= DB::select("select * from v_detalles_ventas where id_ventas = '$id'");
		$dompdf = App::make("dompdf.wrapper");
		$dompdf->loadView("components/imprimir", [
			"ventas" => $ventas,"detalles" => $detalles,
		]);
		return $dompdf->stream('reporte_venta_'."$id".'.pdf');
	
});