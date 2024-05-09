<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\TypeExpenseController;
use App\Http\Controllers\Admin\StudyController;
use App\Http\Controllers\Admin\StatisticsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Admin\PdfController;
use App\Http\Controllers\Admin\RaceController;
use App\Http\Controllers\Admin\RaceRegistrationController;


use Illuminate\Support\Facades\Auth;


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

Route::get('/', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm']);
Auth::routes(['register' => false]);
Route::get('login/{provider}', [LoginController::class, 'redirectToProvider']);
Route::get('{provider}/callback', [LoginController::class,'handleProviderCallback']);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('notas/{id}', [PdfController::class, 'pdfnote']);

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'noCache']], function() {
    Route::get('/', [DashboardController::class, 'index']);

    Route::get('pdf/{id}', [PdfController::class, 'pdf']);

    Route::get('pdf-carrera/{id}', [PdfController::class, 'pdfRace']);

    Route::get('pdf-egreso/{id}', [PdfController::class, 'pdfEgreso']);
    Route::get('pdf-gasto/{id}', [PdfController::class, 'pdfGasto']);
        
    Route::post('ultimo_rx/{id}', [ServiceController::class, 'getrx']);

    //perfil
    Route::view('perfil/editar','admin.editar-perfil');
    Route::put('perfil/editar', [ProfileController::class, 'update']);

    //Password
    Route::view('perfil/cambiar-contrasena', 'admin.cambiar-contrasena');
    Route::post('perfil/cambiar-contrasena', [PasswordController::class, 'update']);

    // usuarios
    Route::get('usuarios', [UserController::class, 'index']);
    Route::get('usuarios/crear', [UserController::class, 'create']);
    Route::post('usuarios/crear', [UserController::class, 'save']);
    Route::get('usuarios/{id}/editar',[UserController::class, 'edit']);
    Route::put('usuarios/{id}/actualizar',[UserController::class, 'update']);
    Route::delete('usuarios/eliminar/{id}',[UserController::class, 'destroy']);
    //permisos
    Route::get('permisos', [PermissionController::class, 'index']);
    Route::view('agregar-permisos', 'admin.permisos.crear');
    Route::post('permiso/crear', [PermissionController::class, 'save']);
    Route::get('permiso/{id}/editar', [PermissionController::class, 'edit']);
    Route::put('permiso/{id}/actualizar', [PermissionController::class, 'update']);
    Route::delete('permiso/eliminar/{id}', [PermissionController::class, 'delete']);

    //roles
    Route::get('roles', [RoleController::class, 'index']);
    Route::view('agregar-roles', 'admin.roles.crear');
    Route::post('roles/crear', [RoleController::class, 'save']);
    Route::get('roles/{id}/editar', [RoleController::class, 'edit']);
    Route::put('roles/{id}/actualizar', [RoleController::class, 'update']);
    Route::delete('roles/eliminar/{id}', [RoleController::class, 'delete']);

    //Servicios
    Route::get('servicios', [ServiceController::class, 'index']);
    Route::get('agregar-servicio', [ServiceController::class, 'create']);
    Route::post('servicios/crear', [ServiceController::class, 'save']);
    Route::get('servicios/{id}/editar', [ServiceController::class, 'edit']);
    Route::put('servicios/{id}/actualizar', [ServiceController::class, 'update']);
    Route::delete('servicios/eliminar/{id}', [ServiceController::class, 'delete']);

    //Tipo de pago
    Route::get('gastos', [ExpenseController::class, 'index']);
    Route::get('agregar-gastos', [ExpenseController::class, 'create']);
    Route::post('gastos/crear', [ExpenseController::class, 'save']);
    Route::get('gastos/{id}/editar', [ExpenseController::class, 'edit']);
    Route::put('gastos/{id}/actualizar', [ExpenseController::class, 'update']);
    Route::delete('gastos/eliminar/{id}', [ExpenseController::class, 'delete']);

    //Sucursales
    Route::get('sucursales', [BranchController::class, 'index']);
    Route::view('agregar-sucursales', 'admin.sucursales.crear');
    Route::post('sucursales/crear', [BranchController::class, 'save']);
    Route::get('sucursales/{id}/editar', [BranchController::class, 'edit']);
    Route::put('sucursales/{id}/actualizar', [BranchController::class, 'update']);
    Route::delete('sucursales/eliminar/{id}', [BranchController::class, 'delete']);

    //Tipo de pago
    Route::get('pagos', [PaymentController::class, 'index']);
    Route::view('agregar-pagos', 'admin.pagos.crear');
    Route::post('pagos/crear', [PaymentController::class, 'save']);
    Route::get('pagos/{id}/editar', [PaymentController::class, 'edit']);
    Route::put('pagos/{id}/actualizar', [PaymentController::class, 'update']);
    Route::delete('pagos/eliminar/{id}', [PaymentController::class, 'delete']);

    //Tipo de gastos
    Route::get('tipos-gastos', [TypeExpenseController::class, 'index']);
    Route::view('agregar-tipos-gastos', 'admin.tipogastos.crear');
    Route::post('tipos-gastos/crear', [TypeExpenseController::class, 'save']);
    Route::get('tipos-gastos/{id}/editar', [TypeExpenseController::class, 'edit']);
    Route::put('tipos-gastos/{id}/actualizar', [TypeExpenseController::class, 'update']);
    Route::delete('tipos-gastos/eliminar/{id}', [TypeExpenseController::class, 'delete']);

    //Estudios
    Route::get('estudios', [StudyController::class, 'index']);
    Route::view('agregar-estudios', 'admin.estudios.crear');
    Route::post('estudios/crear', [StudyController::class, 'save']);
    Route::get('estudios/{id}/editar', [StudyController::class, 'edit']);
    Route::put('estudios/{id}/actualizar', [StudyController::class, 'update']);
    Route::delete('estudios/eliminar/{id}', [StudyController::class, 'delete']);
    
    // Estadísticas
    Route::get('estadisticas-servicios', [StatisticsController::class, 'index']);

    Route::get('estadisticas-gastos', [StatisticsController::class, 'gastos']);

    //Usuarios
    Route::get('usuarios', [UserController::class, 'index']);

    //Password
    Route::view('cambiar-contrasena', 'principal.cambiar-contrasena');
    Route::post('cambiar-contrasena', 'Auth\PasswordController@update');

    //Carreras
    Route::get('carreras', [RaceController::class, 'index']);
    Route::view('carreras/crear', 'admin.carreras.crear');
    Route::post('carreras/crear', [RaceController::class, 'save']);
    Route::get('carreras/{id}/editar', [RaceController::class, 'edit']);
    Route::put('carreras/{id}/actualizar', [RaceController::class, 'update']);
    Route::delete('carreras/eliminar/{id}', [RaceController::class, 'delete']);

    //Participantes
    Route::get('carrera-participantes', [RaceRegistrationController::class, 'index']);
    Route::get('carrera-participantes/crear', [RaceRegistrationController::class, 'create']);
    Route::post('carrera-participantes/crear', [RaceRegistrationController::class, 'save']);
    Route::get('carrera-participantes/{id}/editar', [RaceRegistrationController::class, 'edit']);
    Route::put('carrera-participantes/{id}/actualizar', [RaceRegistrationController::class, 'update']);
    Route::delete('carrera-participantes/eliminar/{id}', [RaceRegistrationController::class, 'delete']);

    Route::get('carrera-estadistica', [RaceRegistrationController::class, 'statics']);

});