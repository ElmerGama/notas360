<?php
use Illuminate\Routing\Router;
use App\Admin\Controllers\GestionController;
use App\Admin\Controllers\PeriodoController;
use App\Admin\Controllers\CursoController;
use OpenAdmin\Admin\Facades\Admin;
//use Dompdf\Dompdf;

Admin::routes();
Route::group([
    'prefix'        => 'admin',
    'namespace'     => 'App\Admin\Controllers',
    'middleware'    => ['web', 'admin'],
    'as'            => 'admin.',
], function (Router $router) {
    $router->get('/', 'HomeController@index')->name('home');

       // Definir la ruta para exportar en PDF
       $router->get('periodos/export-pdf', [PeriodoController::class, 'exportPdf'])->name('periodos.export.pdf');

    $router->resource('gestiones', GestionController::class);
    $router->resource('periodos', PeriodoController::class);
    $router->resource('cursos', CursoController::class);
    // $router->post('cursos/fetch-grados', [CursoController::class, 'fetchGrados'])->name('cursos.fetch-grados');
    
 
});
