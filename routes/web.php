<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SivigilaController;
use App\Http\Controllers\IngresoController;
use App\Http\Controllers\SeguimientoController;
use App\Http\Controllers\RevisionController;
use App\Http\Controllers\SeguimientoOcasionalController;
use App\Http\Controllers\Seguimiento412Controller;
use App\Http\Controllers\Cargue412Controller;
use App\Http\Controllers\AfiliadoController;
use App\Http\Controllers\EmailController;
use App\Exports\vacunaExport;
use Illuminate\Http\Request;


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
    return view('auth/login');
});

//OJO AQUI COMIENZAN LAS RUTAS  DE PAI
Route::get('/afiliado', [AfiliadoController::class, 'index'])->name('afiliado')
->middleware('auth');


// Rutas para el cargue de la información
 Route::get('/import-excel_2', [AfiliadoController::class, 'showImportForm'])->name('import-excel-form_2');//->middleware('auth');
Route::post('/import-excel_2', [AfiliadoController::class, 'importExcel'])
    ->name('import-excel_2')
    ->middleware(['auth', \App\Http\Middleware\IncreaseExecutionTime::class]);
   // ->middleware([\App\Http\Middleware\IncreaseExecutionTime::class]);


// Ruta para obtener vacunas
Route::get('/vacunas/{id}/{numero_carnet}', [AfiliadoController::class, 'getVacunas'])->name('getVacunas');


// // Ruta para el reporte Excel
// Route::get('export-vacunas', function () {
//     return Excel::download(new vacunaExport, 'vacunas.xlsx');
// });

// Ruta para el reporte Excel con fechas
// Ruta para el reporte Excel con fechas
Route::get('export-vacunas', function (Request $request) {
    // Obtener los parámetros de la URL usando el objeto Request inyectado
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    // dd($startDate, $endDate);

    // Verifica que las fechas se reciban correctamente
    if ($startDate && $endDate) {
        return Excel::download(new VacunaExport($startDate, $endDate), 'vacunas.xlsx');
    } else {
        // Manejar el caso en que no se pasen las fechas (por ejemplo, redirigir o mostrar un error)
        return redirect()->back()->withErrors(['msg' => 'Fechas no proporcionadas']);
    }
})->name('exportVacunas');





// Ruta para eliminar registros
Route::delete('/batch_verifications/{id}', [AfiliadoController::class, 'destroy'])->name('batch_verifications.destroy');


//AQUI TERMINAN LAS  RUTAS  DE PAI



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('sivigila', SivigilaController::class)->middleware(['auth', 'increase_execution_time'])
->middleware(['auth', \App\Http\Middleware\IncreaseExecutionTime::class]);

Route::resource('revision', RevisionController::class);

Route::resource('seguimiento_ocasional', SeguimientoOcasionalController::class)->middleware('auth');
Route::resource('new412', Cargue412Controller::class);
Route::resource('new412_seguimiento', Seguimiento412Controller::class);
Route::get('/new412/{id}/{numero_identificacion}/edit', [Cargue412Controller::class, 'edit'])->name('editvariables');




Route::resource('Seguimiento', SeguimientoController::class)->middleware('auth');
Route::get('/search1', 'App\Http\Controllers\SeguimientoController@search')->name('BUSCADOR1');
Route::get('/sivigila/{num_ide_}/{fec_not}/create', 'App\Http\Controllers\SivigilaController@create')->name('detalle_sivigila');
Route::get('/search', 'App\Http\Controllers\SivigilaController@search')->name('BUSCADOR');
Route::get('/alert', 'App\Http\Controllers\SeguimientoController@alerta')->name('ALERTA');

//rutas para reportes en excel
Route::get('/report', [SeguimientoController::class,'resporte'])->name('export');
Route::get('/report1', [IngresoController::class,'reporte'])->name('export1');
Route::get('/report2', [SivigilaController::class,'reporte1'])->name('export2');
Route::get('/report3', [SeguimientoController::class,'reporte2'])->name('export3');
Route::get('/report4', [SivigilaController::class,'reSporte2'])->name('export4');
Route::get('/report5', [SivigilaController::class,'reSporte_sinseguimiento'])->name('export5');
Route::get('/report6', [Seguimiento412Controller::class,'reporte_seguimiento412'])->name('export6');
Route::get('/report7', [Cargue412Controller::class,'reporte1cargue412'])->name('export7');

//RUTAS PDF
Route::get('revision/{id}/reporte','App\Http\Controllers\RevisionController@reportepdf')->name('pdfcertificado');

//RUTAS PARA JALAR DATOS DE SEGUIMIENTOS Y HACER EL VISTO BUENO
Route::get('/revision/{id}/create', 'App\Http\Controllers\RevisionController@create')->name('detalle_revisiones');

//RUTA PARA VER UN DETALLE DE SEGUIMIENTO
Route::get('/Seguimiento/{id}/detail', 'App\Http\Controllers\SeguimientoController@detail')->name('detalleseguimiento');



// Ruta para ver el PDF
Route::get('/seguimiento/view-pdf/{id}', 'App\Http\Controllers\SeguimientoController@viewPDF')->name('seguimiento.view-pdf');

// Ruta para ver el PDF seguimiento 412
Route::get('/seguimiento_412/view-pdf/{id}', 'App\Http\Controllers\Seguimiento412Controller@viewPDF')->name('seguimiento.view-pdf_412');

//ruta para las graficas
Route::get('/grafica-barras', [SeguimientoController::class, 'graficaBarras'])->name('grafica.barras')->middleware('can:view-statistics');

Route::get('/grafica-torta-clasificacion', [SeguimientoController::class, 'graficaTortaClasificacion'])->name('grafica.torta.clasificacion');

// Route::get('/import', 'App\Http\Controllers\SivigilaController@import');



Route::get('/nuevo', 'App\Http\Controllers\SivigilaController@create1')->name('create11');


Route::get('seguimiento_ocasional/create/{id}', 'App\Http\Controllers\SeguimientoOcasionalController@create')->name('seguimiento_ocasional.create');


//RUTAS PARA EL CARGUE DE LA  INFORMACION
Route::get('/import-excel', [Cargue412Controller::class, 'showImportForm'])->name('import-excel-form')->middleware('auth');
Route::post('/import-excel', [Cargue412Controller::class, 'importExcel'])->name('import-excel')->middleware('auth');

//RUTA PARA DESCARGAR MANUAL

Route::get('/download-pdf', 'App\Http\Controllers\SivigilaController@downloadPdf')->name('download.pdf');


// RUTA PARA CARGARA LA TABLA POR  JSON
// Route::get('/obtener-datos-tabla', 'SivigilaController@obtenerDatos')->name('obtener.datos.tabla');

//RUTAS PARA LAS TABLAS CON JAVA SCRIPT Y DATATABLE
Route::get('seguimiento/data', [SeguimientoController::class, 'getData'])->name('seguimiento.data');
Route::get('/obtener-datos-tabla', [SivigilaController::class, 'getData1'])->name('sivigila.data');
Route::post('/sivigila/check-status', [App\Http\Controllers\SivigilaController::class, 'checkStatus'])->name('sivigila.checkStatus');
Route::post('/sivigila/check-ipsprimaria', [App\Http\Controllers\SivigilaController::class, 'checkIpsPrimaria'])->name('sivigila.ipsprimaria');


//RURTA PARA JSON DE ESTADISTICAS   DE LAS TABLAS QUE MUESTRAN PARA LOS INDICADORES
Route::get('/detalle-prestador/{id}', [SeguimientoController::class, 'detallePrestador'])->name('gedetalle_prestador');

Route::get('/detalle-prestador-113/{id}', [SeguimientoController::class, 'detallePrestador_113'])->name('detallePrestador_113');

//RUTA  PARA ENVIO DE CORREO DE  PAI 
Route::post('/send-email', [AfiliadoController::class, 'sendEmail'])->name('send.email');


Route::get('/descargar-formato', [AfiliadoController::class, 'downloadExcel'])->name('download.excel');


//RUTA PARA EL BUSCADOR DINAMICO EN PAI
Route::get('/buscar-afiliados', [AfiliadoController::class, 'buscarAfiliados'])->name('buscar.afiliados');
