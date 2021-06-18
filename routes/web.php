<?php
//Rutas Orlando

use App\Http\Controllers\webController\InstructorController;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;
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

//Ruta Pago 25/09/2020
Route::get('/pago/historial/Validado/{id}', 'webController\PagoController@historial_validacion')->name('pago.historial-verificarpago');

// Ruta Contrato 14/12/2020
Route::get('/contrato/historial/validado/{id}', 'webController\ContratoController@historial_validado')->name('contrato-validado-historial');
Route::get('/contrato/eliminar/{id}', 'webController\ContratoController@delete')->name('eliminar-contrato');
Route::get('/contrato/previsualizacion/{id}', 'webController\ContratoController@pre_contratoPDF')->name('pre_contrato');
Route::get('/prueba', 'webController\ContratoController@prueba');
Route::get('/contrato/reiniciar/{id}', 'webController\ContratoController@contractRestart')->name('reiniciar-contrato');

//Ruta Manual
Route::get('/user/manuales', 'webController\manualController@index')->name('manuales');
// checar cursos
Route::post('/alumnos/sid/checkcursos', 'webController\AlumnoController@checkcursos')->name('alumnos.sid.checkcursos');

//ruta Pago
Route::post('/pago/rechazar_pago', 'webController\PagoController@rechazar_pago')->name('pago-rechazo');
Route::get('/pago/solicitud/modificar/{id}', 'webController\Contratocontroller@mod_solicitud_pago')->name('pago-mod');
Route::post('/pago/savemod/solpa', 'webController\ContratoController@save_mod_solpa')->name('savemod-solpa');
Route::get('/pago/reiniciar/{id}', 'webController\PagoController@pagoRestart')->name('reiniciar-pago');

//Ruta Alta/Baja
Route::get('/cursos/alta-baja/{id}', 'webController\CursosController@alta_baja')->name('curso-alta_baja');
Route::post('/cursos/alta-baja/save','webController\CursosController@alta_baja_save')->name('curso-alta-baja-save');
Route::get('/convenios/alta-baja/{id}', 'webController\ConveniosController@alta_baja')->name('convenio-alta_baja');
Route::post('/Convenios/alta-baja/save','webController\ConveniosController@alta_baja_save')->name('convenio-alta-baja-save');

// Ruta Supre busqueda & misc
Route::post('/supre/busqueda/curso', 'webController\suprecontroller@getcursostats')->name('supre.busqueda.curso');
Route::post('/supre/busqueda/tipo_curso', 'webController\suprecontroller@gettipocurso')->name('supre.busqueda.tipocurso');
Route::post('/supre/busqueda/folio', 'webController\suprecontroller@getfoliostats');
Route::post('/alumnos/sid/municipios', 'webController\AlumnoController@getmunicipios')->name('alumnos.sid.municipios');
Route::post('/supre/validacion/upload_doc','webController\SupreController@doc_valsupre_upload')->name('doc-valsupre-guardar');
Route::post('/supre/busqueda/folios/modal', 'webController\suprecontroller@getfoliostatsmodal')->name('supre.busqueda.foliosmodal');
Route::post('/supre/folio/permiso','webController\SupreController@dar_permiso_folio')->name('folio-permiso-mod');
Route::get('/supre/eliminar/{id}', 'webController\SupreController@delete')->name('eliminar-supre');
Route::get('/supre/reiniciar/{id}', 'webController\SupreController@restartSupre')->name('restart-supre');

//Ruta Instructor
Route::get('/instructor/validar/{id}', 'webController\InstructorController@validar')->name('instructor-validar');
Route::get('/instructor/editar/{id}', 'webController\InstructorController@editar')->name('instructor-editar');
Route::get('/instructor/editar/especialidad-validada/{id}/{idins}', 'webController\InstructorController@edit_especval')->name('instructor-editespectval');
Route::get('/instructor/editar/especialidad-valid/{id}/{idins}/{idesp}', 'webController\InstructorController@edit_especval2')->name('instructor-editespectval2');
Route::get('/instructor/mod/perfil-profesional/{id}/{idins}', 'webController\InstructorController@mod_perfil')->name('instructor-perfilmod');
Route::get('/instructor/alta-baja/{id}', 'webController\InstructorController@alta_baja')->name('instructor-alta_baja');
Route::post('/instructor/rechazo','webController\InstructorController@rechazo_save')->name('instructor-rechazo');
Route::post('/instructor/validado','webController\InstructorController@validado_save')->name('instructor-validado');
Route::post('/instructor/guardar-mod','webController\InstructorController@guardar_mod')->name('instructor-guardarmod');
Route::post('/instructor/saveins','webController\InstructorController@save_ins')->name('saveins');
Route::post('/instructor/espec-ins/guardar','webController\InstructorController@espec_val_save')->name('especinstructor-guardar');
Route::post('/instructor/espec-ins/modificacion/guardar','webController\InstructorController@especval_mod_save')->name('especinstructor-modguardar');
Route::post('/instructor/mod/perfilinstructor/guardar', 'webController\InstructorController@modperfilinstructor_save')->name('modperfilinstructor-guardar');
Route::post('/instructor/alta-baja/save','webController\InstructorController@alta_baja_save')->name('instructor-alta-baja-save');

Route::get('/alumnos_registrados/modificar/index', 'adminController\AlumnoRegistradoModificarController@index')->name('alumno_registrado.modificar.index');
Route::get('/alumnos_registrados/modificar/show/{id}', 'adminController\AlumnoRegistradoModificarController@edit')->name('alumno_registrado.modificar.show');
Route::put('/alumnos_registrados/modificar/update/{id}', 'adminController\AlumnoRegistradoModificarController@update')->name('alumno_registrado.modificar.update');
Route::get('/alumnos_registrados/modificar/delete/{id}/{id_pre}', 'adminController\AlumnoRegistradoModificarController@destroy')->name('alumno_registrado.modificar.eliminar');
// consecutivos/
Route::get('/registros/unidad/index', 'adminController\AlumnoRegistradoModificarController@indexUnidad')->name('registro_unidad.index');
Route::post('/alumnos_registrados/consecutivos', 'adminController\AlumnoRegistradoModificarController@indexConsecutivo')->name('registrado_consecutivo.index');
Route::get('/registrados/consecutivos/index', 'adminController\AlumnoRegistradoModificarController@registradosConsecutivos')->name('registrados.consecutivos');
Route::get('/administracion/index', function () {
    return view('layouts.pages_admin.index');
})->name('administracion.index');
/**
* UNIDADES DE CAPACITACION
*/
Route::get('/unidades/unidad_by_ubicacion/{ubicacion}', 'webController\UnidadController@ubicacion');

/***
 * modificación de roles y permisos -- nuevos registros
 */
Route::get('/usuarios/permisos/index', 'adminController\userController@index')->name('usuario_permisos.index');
Route::get('/usuarios/permisos/perfil/{id}', 'adminController\userController@show')->name('usuarios_permisos.show');
Route::get('/usuarios/profile/{id}', 'adminController\userController@edit')->name('usuarios.perfil.modificar');
Route::get('/permisos/index', 'adminController\PermissionController@index')->name('permisos.index');
Route::get('/roles/index', 'adminController\RolesController@index')->name('roles.index');
Route::get('/roles/modificacion/{id}', 'adminController\RolesController@edit')->name('roles.edit');
Route::get('/roles/create', 'adminController\RolesController@create')->name('roles.create');
Route::get('/permisos/create', 'adminController\PermissionController@create')->name('permisos.crear');
Route::get('/permisos/edit/{id}', 'adminController\PermissionController@edit')->name('permisos.editar');
Route::get('/permisos/roles/index', 'adminController\PermissionController@permiso_rol')->name('permisos.roles.index');
Route::get('/gestor/permisos/roles/profile/{id}', 'adminController\PermissionController@gestorPermisosRoles')->name('gestor.permisos.roles');
Route::get('/usuarios/profile/create/new', 'adminController\userController@create')->name('usuarios.perfil.crear');
Route::post('/usuarios/profile/store', 'adminController\userController@store')->name('usuarios.perfil.store');
Route::post('/gestor/permisos/roles/profile/add', 'adminController\PermissionController@store')->name('gestor.permisos.roles.create');
Route::post('/roles/create/store', 'adminController\RolesController@store')->name('roles.store');
Route::put('/roles/modificacion/update/{id}', 'adminController\RolesController@update')->name('roles.update');
Route::put('/usuarios/profile/update/{id}', 'adminController\userController@update')->name('usuarios_permisos.update');
Route::post('/permisos/store', 'adminController\PermissionController@storePermission')->name('permission.store');
Route::put('/permisos/update/{id}', 'adminController\PermissionController@update')->name('permiso.update');
Route::put('/usuarios/permisos/perfil/rol/{id}', 'adminController\userController@updateRol')->name('usuarios_permisos.rol.edit');
Route::get('/personal/index', 'adminController\PersonalController@index')->name('personal.index');
Route::get('/personal/create', 'adminController\PersonalController@create')->name('personal.crear');
Route::post('/personal/store', 'adminController\PersonalController@store')->name('personal.store');
Route::get('/organo/organo_administrativo/{id}', 'adminController\PersonalController@getAdscripcion');
Route::get('/personal/edit/{id}', 'adminController\PersonalController@edit')->name('personal.edit');
Route::put('/personal/update/{id}', 'adminController\PersonalController@update')->name('personal.update');
/**
 * UNIDADES DE CAPACITACION
 */
Route::get('/unidades/unidades_ubicacion/{ubicacion}', 'webController\UnidadController@ubicacion');
/**
 * Alumnos sice Registrados
 */
Route::get('/alumnos_registrados/sice/index', 'adminController\alumnosRegistroSiceController@index')->name('alumnos_registrados_sice.inicio');
Route::get('/alumnos_registrados/sice/editar/{id}', 'adminController\alumnosRegistroSiceController@edit')->name('registro_alumnos_sice.modificar.show');
Route::put('alumnos_registrados/sice/update/{id}', 'adminController\alumnosRegistroSiceController@update')->name('registro_alumnos_sice.modificar.update');
//



Route::get('/alumno/registro/pdf', 'webController\AlumnoController@pdf_registro')->name('pdf-alumno');

Route::get('/exportarpdf/solicitudsuficiencia', 'webController\presupuestariaController@index')->name('procesodepago');
Auth::routes();

//Ruta supre
Route::get('/supre/solicitud/opc', 'webController\supreController@opcion')->name('solicitud-opcion');
Route::get('/supre/solicitud/folio', 'webController\supreController@solicitud_folios')->name('solicitud-folio');
Route::get('/supre/tabla-pdf/{id}', 'webController\supreController@tablasupre_pdf')->name('tablasupre-pdf');
Route::post('/supre/valsupre_checkmod/', 'webController\supreController@valsupre_checkmod')->name('valsupre-checkmod');

//Ruta
Route::get('/contrato/inicio', 'webController\ContratoController@index')->name('contrato-inicio');
Route::get('/contrato/solicitud-pago/{id}','webController\ContratoController@solicitud_pago')->name('solicitud-pago');
Route::post('/contrato/save','webController\ContratoController@contrato_save')->name('contrato-save');
Route::post('/contrato/save-mod','webController\ContratoController@save_mod')->name('contrato-savemod');
Route::post('/contrato/rechazar-contrato','webController\ContratoController@rechazar_contrato')->name('contrato-rechazar');
Route::get('/contrato/validar/{id}', 'webController\ContratoController@validar_contrato')->name('contrato-validar');
Route::get('/contrato/{id}', 'webController\ContratoController@contrato_pdf')->name('contrato-pdf');
Route::post('/contrato/save-doc','webController\ContratoController@save_doc')->name('save-doc');
Route::get('/contrato/valcontrato/{id}', 'webController\ContratoController@valcontrato')->name('valcontrato');
Route::get('/contrato/modificar/{id}', 'webController\ContratoController@modificar')->name('contrato-mod');
Route::get('/contrato/solicitud-pago/pdf/{id}', 'webController\ContratoController@solicitudpago_pdf')->name('solpa-pdf');
Route::post('/directorio/getdirectorio','webController\ContratoController@get_directorio')->name('get-directorio');
Route::get('/pagos/documento/{docs}', 'webController\ContratoController@docs')->name('get-docs');

//Ruta Pago
Route::get('/pago/vista/{id}', 'webController\PagoController@mostrar_pago')->name('mostrar-pago');

// Ruta Validacion
Route::post('/supre/validacion/Rechazado', 'webController\supreController@supre_rechazo')->name('supre-rechazo');
Route::post('/supre/validacion/Validado', 'webController\supreController@supre_validado')->name('supre-validado');
Route::get('/supre/validacion/pdf/{id}', 'webController\supreController@valsupre_pdf')->name('valsupre-pdf');
Route::get('/supre/pdf/{id}', 'webController\supreController@supre_pdf')->name('supre-pdf');

/**
 * Ruta que muestra los archivos protegidos con un middleware de auth
 */
Route::get('/storage/uploadFiles/{folder}/{id}/{slug}', 'webController\DocumentoController@show')->middleware('auth')->name('documentos.show');

/**
 * Middleware con permisos de los usuarios de autenticacion
 */
Route::middleware(['auth'])->group(function () {
    /**
     * Desarrollado por Adrian y Daniel
     */
    //Route::get('/alumnos/indice', 'webController\AlumnoController@index')
        //   ->name('alumnos.index')->middleware('can:alumnos.index');
    Route::get('/alumnos/indice', 'webController\AlumnoController@index')
        ->name('alumnos.index')->middleware('can:alumnos.index');

    Route::get('alumnos/sid', 'webController\AlumnoController@create')
        ->name('alumnos.preinscripcion')->middleware('can:alumnos.inscripcion-paso1');
    Route::get('alumnos/sid/cerss', 'webController\AlumnoController@createcerss')->name('preinscripcion.cerss');
    Route::post('alumnos/sid/cerss/save', 'webController\AlumnoController@storecerss')->name('preinscripcion.cerss.save'); // guardar preiscripcion cerss
    Route::get('alumnos/sid/cerss/show/{id}', 'webController\AlumnoController@showCerss')->name('preinscripcion.cerss.show');
    Route::get('alumnos/sid/cerss/update/{id}', 'webController\AlumnoController@updateCerss')->name('alumnos.cerss.update');
    Route::put('alumnos/sid/cerss/update_cerss/{idPreinscripcion}', 'webController\AlumnoController@updatedCerssNew')->name('preinscripcion.cerss.modificar');
    Route::post('alumnos/sid/cerss/numero-expediente/generar', 'webController\AlumnoController@getNumeroExpediente')->name('alumnos.sid.cerss.consecutivos');
    Route::post('/alumnos/save', 'webController\AlumnoController@store')->name('alumnos.save');
    // alumnos
    Route::get('/alumnos/preinscripcion/paso2/{id}', 'webController\AlumnoController@steptwo')->name('alumnos.preinscripcion.paso2')
    ->middleware('can:alumno.inscripcion-documento');
    Route::get('alumnos/sid-paso2/{id}', 'webController\AlumnoController@show')
        ->name('alumnos.presincripcion-paso2')->middleware('can:alumnos.inscripcion-paso3');
    Route::post('alumnos/sid/update', 'webController\AlumnoController@update')->name('alumnos.update-sid')->middleware('can:alumnos.inscripcion.store');
    Route::post('alumnos/sid/update_pregistro', 'webController\AlumnoController@update_pregistro')->name('alumnos.update.documentos.registro');
    Route::put('alumnos/sid/update_registro/{idregistrado}', 'webController\AlumnoRegistradoController@update')->name('alumnos-cursos.update');
    // modificacion alumnos
    Route::get('alumnos/modificar/sid-paso2/{id}', 'webController\AlumnoRegistradoController@edit')->name('alumnos.update.registro')
    ->middleware('can:alumno.inscrito.edit');
    // modificar la preinscripcion
    Route::get('alumnos/modificar/sid/{id}', 'webController\AlumnoController@showUpdate')->name('alumnos.presincripcion-modificar');
    Route::get('alumnos/modificar/jefe-unidad/sid/{id}', 'webController\AlumnoController@modifyUpdateChief')->name('alumnos.modificar-jefe-unidad');
    Route::put('alumnos/sid/modificar/{idAspirante}', 'webController\AlumnoController@updateSid')->name('sid.modificar');
    Route::put('alumnos/sid/modificar/jefe-unidad/{idAspirante}', 'webController\AlumnoController@updateSidJefeUnidad')->name('sid.modificar-jefe-unidad')->middleware('can:alumnos.inscripcion-jefe-unidad-update');
    Route::get('alumnos/sid/documento/{nocontrol}', 'webController\AlumnoRegistradoController@getDocumentoSid')->name('documento.sid');
    Route::get('alumnos/sid/documento/cerrs/{nocontrol}', 'webController\AlumnoRegistradoController@getDocumentoCerrsSid')->name('documento.sid_cerrs');
    // nueva ruta
    Route::get('alumnos/registrados/{id}', 'webController\AlumnoRegistradoController@show')->name('alumnos.inscritos.detail')
    ->middleware('can:alumno.inscrito.show');
    Route::get('alumnos/registrados', 'webController\AlumnoRegistradoController@index')->name('alumnos.inscritos')
    ->middleware('can:alumnos.inscritos.index');
    // supre
    Route::post("/supre/save","webController\supreController@store")->name('store-supre');
    // documentos pdf Desarrollado por Adrian
    // Route::get('/exportarpdf/presupuestaria', 'webController\presupuestariaController@export_pdf')->name('presupuestaria');
    Route::get('/exportarpdf/contratohonorarios', 'webController\presupuestariaController@propa')->name('contratohonorarios');
    Route::get('/exportarpdf/solicitudsuficiencia/{id}', 'webController\presupuestariaController@export_pdf')->name('solicitudsuficiencia');
    Route::post('/alumnos/sid/cursos', 'webController\AlumnoController@getcursos')->name('alumnos.sid.cursos');
    Route::post('/alumnos/sid/cursos_update', 'webController\AlumnoController@getcursos_update'); // QUITAR O MODIFICAR

    /**
     * ============================================================================================================================================
     * CURSOS
     * ============================================================================================================================================
     */
    Route::get('/curso/inicio', 'webController\CursosController@index')->name('curso-inicio')->middleware('can:cursos.index');
    Route::get('/cursos/crear', 'webController\CursosController@create')->name('frm-cursos')->middleware('can:cursos.create');
    Route::get('/cursos/editar-catalogo/{id}', 'webController\CursosController@show')->name('cursos-catalogo.show')->middleware('can:cursos.show');
   // Route::get('/cursos/crear', 'webController\CursoValidadoController@cv_crear')->name('cv_crear');
   Route::put('/cursos/actualiza-catalogo/{id}', 'webController\CursosController@update')->name('cursos-catalogo.update')->middleware('can:cursos.update');
   Route::post('cursos/guardar-catalogo', 'webController\CursosController@store')->name('cursos.guardar-catalogo')->middleware('can:cursos.store');
   Route::get('/cursos/especialidad_by_area/{id_especialidad}', 'webController\CursosController@get_by_area')->name('cursos.get_by_area');
    /**
     * obtener toda la información del curso por id
     */
    Route::get('cursos/get_by_id/{idCurso}', 'webController\CursosController@get_by_id')->name('cursos.get_by_id');
    /**
     * ============================================================================================================================================
     * CURSOS END
     * ============================================================================================================================================
     */

    /**
     * UNIDADES DE CAPACITACION
     */
    Route::get('/unidades/unidades_by_ubicacion/{ubicacion}', 'webController\UnidadController@ubicacion')->name('unidades.get_by_ubicacion');
    /**
     * contratos Desarrollando por Daniel
     */
    Route::get('/contratos/crear/{id}', 'webController\ContratoController@create')->name('contratos.create');


    Route::get('/', function () {
        return view('layouts.pages.home');
    });
    Route::get('/home', function() {
        return view('layouts.pages.home');
    })->name('home');


    /*
    Route::get('/', function () {
        return view('layouts.pages.home');
    });
    Route::get('/home', function() {
        return view('layouts.pages.home');
    })->name('home');
    /***
     * Desarrollado por Orlando
     */

    // Crea pago
    Route::get('/pago/inicio', 'webController\PagoController@index')->name('pago-inicio');
    Route::get('/pago/crear/{id}', 'webController\PagoController@crear_pago')->name('pago-crear');
    Route::post('/pago/guardar', 'webController\PagoController@guardar_pago')->name('pago-guardar');
    Route::get('/pago/modificar', 'webController\PagoController@modificar_pago')->name('pago-modificar');
    Route::post('/pago/fill', 'webController\PagoController@fill');
    // cambiando status
    Route::get('/pago/verificar_pago/{id}', 'webController\PagoController@show')->name('pago.verificarpago');
    Route::post('/pago/validar_pago', 'webController\PagoController@guardar_pago')->name('pago.validar');
    Route::get('/pago/validacion/{idfolio}', 'webController\PagoController@pago_validar')->name('pago.validacion');

    // Crea instructor
    Route::get('/instructor/inicio', 'webController\InstructorController@index')->name('instructor-inicio');
    Route::get('/instructor/crear', 'webController\InstructorController@crear_instructor')->name('instructor-crear');
    Route::post('/instructor/guardar', 'webController\InstructorController@guardar_instructor')->name('instructor-guardar');
    Route::get('/instructor/ver/{id}', 'webController\InstructorController@ver_instructor')->name('instructor-ver');
    Route::get('/instructor/add/perfil-profesional/{id}', 'webController\InstructorController@add_perfil')->name('instructor-perfil');
    Route::get('/instructor/add/curso-impartir/{id}','webController\InstructorController@add_cursoimpartir')->name('instructor-curso');
    Route::post('/perfilinstructor/guardar', 'webController\InstructorController@perfilinstructor_save')->name('perfilinstructor-guardar');
    Route::get('/instructor/curso-impartir/form/{id}/{idins}', 'webController\InstructorController@cursoimpartir_form')->name('cursoimpartir-form');
    Route::get('/instructor/crear-institucional/{id}', 'webController\InstructorController@institucional')->name('instructor-institucional-crear');
    Route::post('/instructor/institucional/guardar', 'webController\InstructorController@institucional_save')->name('instructor-institucional-save');

    // Solicitud de Suficiencia Presupuestal
    Route::get('/supre/solicitud/inicio', 'webController\supreController@solicitud_supre_inicio')
           ->name('supre-inicio')->middleware('can:supre.index');
    Route::get('/supre/solicitud/crear', 'webController\supreController@frm_formulario')
           ->name('frm-supre')->middleware('can:supre.create');
    Route::post('/supre/solicitud/guardar',"webController\supreController@store")
           ->name('solicitud-guardar');
    Route::get('/supre/solicitud/modificar/{id}', 'webController\supreController@solicitud_modificar')
         ->name('modificar_supre')->middleware('can:supre.edit');
    Route::post('/supre/solicitud/mod-save',"webController\supreController@solicitud_mod_guardar")->name('supre-mod-save');

    // Validar Cursos
    Route::get('/cursos_validados/inicio', 'webController\CursoValidadoController@cv_inicio')->name('cursos_validados.index');
    Route::post('/cursos/fill1', 'webController\CursoValidadoController@fill1');
    Route::post("/cursos/guardar","webController\CursoValidadoController@cv-guardar")->name('addcv');

    // Validacion de Suficiencia Presupuestal
    Route::get('/supre/validacion/inicio', 'webController\supreController@validacion_supre_inicio')->name('vasupre-inicio');
    Route::get('/supre/validacion/{id}', 'webController\supreController@validacion')->name('supre-validacion');
    /**
     * agregado en 06 de marzo del 2020
     */
    Route::get('/convenios/indice', 'webController\ConveniosController@index')->name('convenios.index')
    ->middleware('can:convenios.index');
    Route::get('/convenios/crear', 'webController\ConveniosController@create')->name('convenio.create')
    ->middleware('can:convenios.create');
    Route::post('/convenios/guardar', 'webController\ConveniosController@store')->name('convenios.store')
    ->middleware('can:convenios.store');
    //Route::get('/convenios/show/{id}', 'UserProfileController@show')->name('convenios.show');
    Route::get('/convenios/edit/{id}', 'webController\ConveniosController@edit')->name('convenios.edit')
    ->middleware('can:convenios.edit');
    Route::put('/convenios/modificar/{id}', 'webController\ConveniosController@update')->name('convenios.update')
    ->middleware('can:convenios.update');
    Route::get('/convenios/mostrar/{id}', 'webController\ConveniosController@show')->name('convenios.show')
    ->middleware('can:convenios.show');
    Route::post('/convenios/sid/municipios', 'webController\ConveniosController@getmunicipios');
    /**
     * agregando financiero rutas -- DMC
     */
    Route::get('financiero/indice', 'webController\FinancieroController@index')
           ->name('financiero.index');

        /*TABLERO DE CONTROL RPN*/
    Route::get('/tablero', 'TableroControlller\MetasController@index')->name('tablero.metas.index');
    Route::get('/tablero/metas', 'TableroControlller\MetasController@index')->name('tablero.metas.index');
    Route::post('/tablero/metas', 'TableroControlller\MetasController@index')->name('tablero.metas.index');

    Route::get('/tablero/unidades', 'TableroControlller\UnidadesController@index')->name('tablero.unidades.index');
    Route::post('/tablero/unidades', 'TableroControlller\UnidadesController@index')->name('tablero.unidades.index');

    Route::get('/tablero/cursos', 'TableroControlller\CursosController@index')->name('tablero.cursos.index');
    Route::post('/tablero/cursos', 'TableroControlller\CursosController@index')->name('tablero.cursos.index');


     /* SUPERVISIONES ESCOLAR RPN*/
    Route::get('/supervision/escolar', 'supervisionController\EscolarController@index')->name('supervision.escolar');
    Route::post('/supervision/escolar', 'supervisionController\EscolarController@index')->name('supervision.escolar');
    Route::post('/supervision/curso', 'supervisionController\EscolarController@curso')->name('supervision.curso');
    Route::get('/supervision/curso/{id}', 'supervisionController\EscolarController@curso')->name('supervision.curso');

    Route::get('/supervisiones/escolar/url/generar', 'supervisionController\UrlController@generarUrl')->name('supervision.escolar.url.generar');
    Route::get('/supervisiones/escolar/enviar', 'supervisionController\EscolarController@updateCurso')->name('supervision.escolar.update');
    Route::post('/supervisiones/escolar/enviar', 'supervisionController\EscolarController@updateCurso')->name('supervision.escolar.update');

     /* SUPERVISIONES INSTRUCTORES RPN*/
    Route::get('/supervision/instructor/revision/{id}', 'supervisionController\InstructorController@revision')->name('supervision.instructor.revision');
    Route::post('/supervision/instructores/guardar', 'supervisionController\InstructorController@update')->name('supervision.instructor.guardar');

    /*SUPERVISIONES ALUMNOS RPN*/
    Route::get('/supervisiones/alumno/lst', 'supervisionController\AlumnoController@lista')->name('supervision.alumno.lst');
    Route::get('/supervision/alumno/revision/{id}', 'supervisionController\AlumnoController@revision')->name('supervision.alumno.revision');
    Route::post('/supervisiones/alumnos/guardar', 'supervisionController\AlumnoController@update')->name('supervision.alumno.guardar');


    /* SUPERVISION CONTROL DE CALIDAD RPN*/
    Route::get('/supervision/calidad', 'supervisionController\CalidadController@index')->name('supervision.calidad');
    Route::post('/supervision/calidad', 'supervisionController\CalidadController@index')->name('supervision.calidad');

    /*ENCUESTA
    Route::get('/encuesta/generar/url', 'supervisionController\EncuestaController@generarUrl')->name('encuesta.generar.url');
    */

    /*SUPERVISION UNIDADES RPN*/
    Route::get('/supervision/unidades', 'supervisionController\UnidadesController@index')->name('supervision.unidades');
    Route::post('/supervision/unidades', 'supervisionController\UnidadesController@index')->name('supervision.unidades');
    Route::get('/supervision/unidades/cursos/{id}', 'supervisionController\UnidadesController@cursos')->name('supervision-unidades-cursos');

    /*REPORTES SICE RPN*/
    Route::get('/reportes/cursos', 'reportesController\cursosController@index')->name('reportes.cursos.index')->middleware('can:reportes.cursos');
    Route::post('/reportes/asist/pdf', 'reportesController\cursosController@asistencia')->name('reportes.asist.pdf');
    Route::post('/reportes/calif/pdf', 'reportesController\cursosController@calificaciones')->name('reportes.calif.pdf');
    Route::post('/reportes/ins/pdf', 'reportesController\cursosController@riacIns')->name('reportes.ins.pdf');
    Route::post('/reportes/acred/pdf', 'reportesController\cursosController@riacAcred')->name('reportes.acred.pdf');
    Route::post('/reportes/cert/pdf', 'reportesController\cursosController@riacCert')->name('reportes.cert.pdf');
    Route::post('/reportes/const/xls', 'reportesController\cursosController@xlsConst')->name('reportes.const.xls');
    /*REPORTE 911 AGC*/
    Route::get('/reportes/911', 'reportesController\formato911Controller@showForm')->name('reportes.911.showForm')->middleware('can:reportes.911');
    Route::post('/reportes/911/pdf', 'reportesController\formato911Controller@store')->name('contacto');

    Route::get('reportes/formato_t_reporte/index', function () {
        return view('layouts.pages.reportes.formato_t_reporte');
    })->name('reportes.formatoT');

    //Route::get('/reportes/arc01','pdfcontroller@arc')->name('pdf.generar');
    Route::post('/reportes/arc01','pdfcontroller@arc')->name('pdf.generar');
    Route::get('/reportes/vista_911','pdfcontroller@index')->name('reportes.vista_911');
    Route::post('/reportes/vista_911','pdfcontroller@index')->name('reportes.vista_911');
    Route::get('/reportes/vista_arc','pdfcontroller@index')->name('reportes.vista_arc')->Middleware('can:academicos.arc');
    Route::get('/reportes/vista_ft','ftcontroller@index')->name('vista_formatot');
    Route::post('/reportes/vista_ft','ftcontroller@cursos')->name('formatot.cursos');
    Route::post('/reportes/memo/','ftcontroller@memodta')->name('memo_dta');

    /*Grupos RPN*/
    //Calificaciones
    Route::get('/grupos/calificaciones', 'Grupos\calificacionesController@index')->name('grupos.calificaciones')->middleware('can:grupos.calificaciones');
    Route::post('/grupos/calificaciones/buscar', 'Grupos\calificacionesController@search')->name('grupos.calificaciones.buscar')->middleware('can:grupos.calificaciones.buscar');
    Route::get('/grupos/calificaciones/buscar', 'Grupos\calificacionesController@search')->name('grupos.calificaciones.buscar')->middleware('can:grupos.calificaciones.buscar');
    Route::post('/grupos/calificaciones/guardar', 'Grupos\calificacionesController@update')->name('grupos.calificaciones.guardar')->middleware('can:grupos.calificaciones.guardar');
    Route::get('/grupos/calificaciones/guardar', 'Grupos\calificacionesController@update')->name('grupos.calificaciones.guardar')->middleware('can:grupos.calificaciones.guardar');
    //Asignacion de Folio
    Route::get('/grupos/asignarfolios', 'Grupos\asignarfoliosController@index')->name('grupos.asignarfolios')->middleware('can:grupos.asignarfolios');
    Route::post('/grupos/asignarfolios', 'Grupos\asignarfoliosController@index')->name('grupos.asignarfolios')->middleware('can:grupos.asignarfolios');
    Route::post('/grupos/asignarfolios/guardar', 'Grupos\asignarfoliosController@store')->name('grupos.asignarfolios.guardar')->middleware('can:grupos.asignarfolios.guardar');
    Route::get('/grupos/asignarfolios/guardar', 'Grupos\asignarfoliosController@store')->name('grupos.asignarfolios.guardar')->middleware('can:grupos.asignarfolios.guardar');
     //Cancelación de Folios
    Route::get('/grupos/cancelacionfolios', 'Grupos\cancelacionfoliosController@index')->name('grupos.cancelacionfolios')->middleware('can:grupos.cancelacionfolios');
    Route::post('/grupos/cancelacionfolios', 'Grupos\cancelacionfoliosController@index')->name('grupos.cancelacionfolios')->middleware('can:grupos.cancelacionfolios');
    Route::post('/grupos/cancelacionfolios/guardar', 'Grupos\cancelacionfoliosController@store')->name('grupos.cancelacionfolios.guardar')->middleware('can:grupos.cancelacionfolios.guardar');
    Route::get('/grupos/cancelacionfolios/guardar', 'Grupos\cancelacionfoliosController@store')->name('grupos.cancelacionfolios.guardar')->middleware('can:grupos.cancelacionfolios.guardar');


    /*Grupos Consultas*/
    Route::get('/grupos/consultas', 'Grupos\consultasController@index')->name('grupos.consultas')->middleware('can:grupos.consultas');
    Route::post('/grupos/consultas', 'Grupos\consultasController@index')->name('grupos.consultas')->middleware('can:grupos.consultas');
    Route::post('/grupos/consultas/calificaciones', 'Grupos\consultasController@calificaciones')->name('grupos.consultas.calificaciones')->middleware('can:grupos.consultas.calificaciones');
    Route::post('/grupos/consultas/folios', 'Grupos\consultasController@folios')->name('grupos.consultas.folios')->middleware('can:grupos.consultas.folios');

    /*Solicitudes(DTA) RPN*/
    //Folios
    Route::get('/solicitudes/folios', 'Solicitudes\foliosController@index')->name('solicitudes.folios')->middleware('can:solicitudes.folios');
    Route::post('/solicitudes/folios', 'Solicitudes\foliosController@index')->name('solicitudes.folios')->middleware('can:solicitudes.folios');
    Route::post('/solicitudes/folios/guardar', 'Solicitudes\foliosController@store')->name('solicitudes.folios.guardar')->middleware('can:solicitudes.folios.guardar');
    Route::get('/solicitudes/folios/guardar', 'Solicitudes\foliosController@store')->name('solicitudes.folios.guardar')->middleware('can:solicitudes.folios.guardar');
    Route::post('/solicitudes/folios/editar', 'Solicitudes\foliosController@edit')->name('solicitudes.folios.edit')->middleware('can:solicitudes.folios.edit');
    Route::get('/solicitudes/folios/editar', 'Solicitudes\foliosController@edit')->name('solicitudes.folios.edit')->middleware('can:solicitudes.folios.edit');
    Route::get('/storage/uploadFiles/DTA/solicitud_folios/{pdf}', 'Solicitudes\foliosController@pdf')->name('solicitudes.folios.pdf');
    //Cancelación y Eliminacion de Folios
    Route::get('/solicitudes/cancelacionfolios', 'Solicitudes\cancelacionfoliosController@index')->name('solicitudes.cancelacionfolios')->middleware('can:solicitudes.cancelacionfolios');
    Route::post('/solicitudes/cancelacionfolios', 'Solicitudes\cancelacionfoliosController@index')->name('solicitudes.cancelacionfolios')->middleware('can:solicitudes.cancelacionfolios');
    Route::post('/solicitudes/cancelacionfolios/guardar', 'Solicitudes\cancelacionfoliosController@store')->name('solicitudes.cancelacionfolios.guardar')->middleware('can:solicitudes.cancelacionfolios.guardar');
    Route::get('/solicitudes/cancelacionfolios/guardar', 'Solicitudes\cancelacionfoliosController@store')->name('solicitudes.cancelacionfolios.guardar')->middleware('can:solicitudes.cancelacionfolios.guardar');

     /*CONSULTAS*/
     /*FOLIOS ASIGNADOS*/
    Route::get('/consultas/folios', 'Consultas\foliosController@index')->name('consultas.folios')->middleware('can:consultas.folios');
    Route::post('/consultas/folios', 'Consultas\foliosController@index')->name('consultas.folios')->middleware('can:consultas.folios');
    Route::post('/consultas/folios/xls', 'Consultas\foliosController@xls')->name('consultas.folios.xls');
     /*LOTES DE FOLIOS*/
    Route::get('/consultas/lotes', 'Consultas\lotesController@index')->name('consultas.lotes')->middleware('can:consultas.lotes');
    Route::post('/consultas/lotes', 'Consultas\lotesController@index')->name('consultas.lotes')->middleware('can:consultas.lotes');
    Route::post('/consultas/lotes/xls', 'Consultas\lotesController@xls')->name('consultas.lotes.xls');

   /*CURSOS FINALIZADOS*/
    Route::get('/consultas/cursosfinalizados', 'Consultas\cursosfinalizadosController@index')->name('consultas.cursosfinalizados')->middleware('can:consultas.cursosfinalizados');
    Route::post('/consultas/cursosfinalizados', 'Consultas\cursosfinalizadosController@index')->name('consultas.cursosfinalizados')->middleware('can:consultas.cursosfinalizados');
    Route::post('/consultas/cursosfinalizados/xls', 'Consultas\cursosfinalizadosController@xls')->name('consultas.cursosfinalizados.xls');
    /*CURSOS APERTURADOS*/
    Route::get('/consultas/cursosaperturados', 'Consultas\cursosaperturadosController@index')->name('consultas.cursosaperturados')->middleware('can:consultas.cursosaperturados');
    Route::post('/consultas/cursosaperturados', 'Consultas\cursosaperturadosController@index')->name('consultas.cursosaperturados')->middleware('can:consultas.cursosaperturados');
    Route::post('/consultas/cursosaperturados/xls', 'Consultas\cursosaperturadosController@xls')->name('consultas.cursosaperturados.xls');


    /*VINCULACION->PREINSCRIPCION=> NUEVO GRUPO RPN*/
    Route::get('/preinscripcion/grupo', 'Preinscripcion\grupoController@index')->name('preinscripcion.grupo');
    Route::get('/preinscripcion/grupo/cmbcursos', 'Preinscripcion\grupoController@cmbcursos')->name('preinscripcion.grupo.cmbcursos');
    Route::post('/preinscripcion/grupo/guardar', 'Preinscripcion\grupoController@save')->name('preinscripcion.grupo.save');
    Route::post('/preinscripcion/grupo/update', 'Preinscripcion\grupoController@update')->name('preinscripcion.grupo.update');
    Route::get('/preinscripcion/grupo/nuevo', 'Preinscripcion\grupoController@nuevo')->name('preinscripcion.grupo.nuevo');
    Route::post('/preinscripcion/grupo/nuevo', 'Preinscripcion\grupoController@nuevo')->name('preinscripcion.grupo.nuevo');
    Route::post('/preinscripcion/grupo/turnar', 'Preinscripcion\grupoController@turnar')->name('preinscripcion.grupo.turnar');
    Route::get('/preinscripcion/grupo/eliminar', 'Preinscripcion\grupoController@delete')->name('preinscripcion.grupo.eliminar');

    /*VINCULACION->PREINSCRIPCION=> BUSCAR GRUPO RPN*/
    Route::get('/preinscripcion/buscar', 'Preinscripcion\buscarController@index')->name('preinscripcion.buscar');
    Route::post('/preinscripcion/buscar', 'Preinscripcion\buscarController@index')->name('preinscripcion.buscar');
    Route::get('/preinscripcion/show', 'Preinscripcion\buscarController@show')->name('preinscripcion.show');
    Route::post('/preinscripcion/show', 'Preinscripcion\buscarController@show')->name('preinscripcion.show');

    /*Solucitud Unidad Depto Académico*/
    /*Solicitud de Apertura ARC01*/
    Route::get('/solicitud/apertura', 'Solicitud\aperturaController@index')->name('solicitud.apertura')->middleware('can:solicitud.apertura');
    Route::post('/solicitud/apertura', 'Solicitud\aperturaController@index')->name('solicitud.apertura')->middleware('can:solicitud.apertura');
    Route::get('/solicitud/apertura/cgral', 'Solicitud\aperturaController@cgral')->name('solicitud.apertura.cgral');
    Route::post('/solicitud/apertura/cgral', 'Solicitud\aperturaController@cgral')->name('solicitud.apertura.cgral');
    Route::get('/solicitud/apertura/mexon', 'Solicitud\aperturaController@mexoneracion')->name('solicitud.apertura.mexon');
    Route::post('/solicitud/apertura/mexon', 'Solicitud\aperturaController@mexoneracion')->name('solicitud.apertura.mexon');
    Route::post('/solicitud/apertura/guardar', 'Solicitud\aperturaController@store')->name('solicitud.apertura.guardar')->middleware('can:solicitud.apertura.guardar');
    Route::get('/solicitud/apertura/guardar', 'Solicitud\aperturaController@store')->name('solicitud.apertura.guardar')->middleware('can:solicitud.apertura.guardar');
    Route::post('/solicitud/apertura/turnar', 'Solicitud\aperturaController@turnar')->name('solicitud.apertura.turnar')->middleware('can:solicitud.apertura.turnar');
    Route::get('/solicitud/apertura/turnar', 'Solicitud\aperturaController@turnar')->name('solicitud.apertura.turnar')->middleware('can:solicitud.apertura.turnar');

    Route::post('/solicitud/apertura/aceptar', 'Solicitud\aperturaController@aperturar')->name('solicitud.apertura.aceptar')->middleware('can:solicitud.apertura.aceptar');
    Route::get('/solicitud/apertura/aceptar', 'Solicitud\aperturaController@aperturar')->name('solicitud.apertura.aceptar')->middleware('can:solicitud.apertura.aceptar');


});

/*SUPERVISION ESCOLAR Y ENCUESTA RPN*/
Route::get('/form/instructor/{url}', 'supervisionController\UrlController@form')->name('form.instructor');
Route::post('/form/instructor-guardar', 'supervisionController\client\frmInstructorController@guardar')->middleware('checktoken');
Route::get('/form/alumno/{url}', 'supervisionController\UrlController@form')->name('form.alumno');
Route::post('/form/alumno-guardar', 'supervisionController\client\frmAlumnoController@guardar')->middleware('checktoken');
Route::get('/form/msg/{id}', 'supervisionController\UrlController@msg');

Route::get('/encuesta/form/{url}','supervisionController\EncuestaController@encuesta')->name('encuesta');
Route::post('/encuesta/save','supervisionController\EncuestaController@encuesta_save')->name('encuesta.save');

/*Reporte Planeación 04012021-14062021*/
Route::get('/planeacion/reporte', 'webController\supreController@planeacion_reporte')->name('planeacion.reporte');
Route::get('/planeacion/reporte/cancelados', 'webController\supreController@cancelados_reporte')->name('planeacion.reporte-cancelados');
Route::post('/planeacion/reporte/pdf','webController\supreController@planeacion_reportepdf')->name('planeacion.reportepdf');
Route::post('/directorio/getcurso','webController\supreController@get_curso')->name('get-curso');
Route::post('/directorio/getins','webController\supreController@get_ins')->name('get-ins');
Route::post('/planeacion/reporte-cancelados/pdf','webController\supreController@planeacion_reporte_canceladospdf')->name('planeacion.reporte-canceladospdf');

/* Modulo CERSS 11012021 */
Route::get('/cerss/inicio', 'webController\CerssController@index')->name('cerss.inicio')
    ->middleware('can:cerss.inicio');
Route::get('/cerss/formulario', 'webController\CerssController@create')->name('cerss.frm');
Route::get('/cerss/modificar/{id}', 'webController\CerssController@update')->name('cerss.update');
Route::post('/cerss/formulario/save', 'webController\CerssController@save')->name('cerss.save');
Route::post('/cerss/modificar/save', 'webController\CerssController@update_save')->name('cerss.save-update');
Route::post('/cerss/modificar/save-titular', 'webController\CerssController@updateTitular_save')->name('cerss.savetitular-update');

/* Modulo áreas */
Route::get('/areas/inicio', 'webController\AreasController@index')->name('areas.inicio')->middleware('can:areas.inicio');
Route::get('/areas/agregar', 'webController\AreasController@create')->name('areas.agregar')->middleware('can:areas.formulario-creacion');
Route::get('/areas/modificar/{id}', 'webController\AreasController@update')->name('areas.modificar')->middleware('can:areas.formulario-actualizar');
Route::post('/areas/guardar', 'webController\AreasController@save')->name('areas.guardar')->middleware('can:areas.guardar-nueva-area');
Route::post('/areas/modificar/save', 'webController\AreasController@update_save')->name('areas.update_save')->middleware('can:areas.guardar-modificacion');
Route::get('/areas/{id}', 'webController\AreasController@destroy')->name('areas.destroy');

/* Modulo especialidades */
Route::get('/especialidades/inicio', 'webController\EspecialidadesController@index')->name('especialidades.inicio')->middleware('can:especialidades.inicio');
Route::get('/especialidades/agregar', 'webController\EspecialidadesController@create')->name('especialidades.agregar')->middleware('can:especialidades.formulario-creacion');
Route::post('/especialidades/guardar', 'webController\EspecialidadesController@store')->name('especialidades.guardar')->middleware('can:especialidades.guardar-nueva-especialidad');
Route::get('/especialidades/modificar/{id}', 'webController\EspecialidadesController@edit')->name('especialidades.modificar')->middleware('can:especialidades.formulario-actualizar');
Route::post('/especialidades/modificar/save/{id}', 'webController\EspecialidadesController@update')->name('especialidades.update')->Middleware('can:especialidades.guardar-modificacion');
Route::get('/especialidades/{id}', 'webController\EspecialidadesController@destroy')->name('especialidades.destroy');


/* Modulo instituto*/
Route::get('/instituto/inicio', 'webController\InstitutoController@index')->name('instituto.inicio')->middleware('can:instituto.inicio');
Route::post('/instituto/guardar', 'webController\InstitutoController@store')->name('instituto.guardar')->middleware('can:instituto.guardar-modificacion');

/*c Modulo tbl_unidades 0302021*/
Route::get('/unidades/inicio', 'webController\UnidadesController@index')->name('unidades.inicio')->middleware('can:unidades.index');
Route::get('/unidades/modificar/{id}', 'webController\UnidadesController@editar')->name('unidades.editar')->middleware('can:unidades.editar');
Route::post('/unidades/modificar/guardar', 'webController\UnidadesController@update')->name('unidades-actualizar');

/* Modulo exoneraciones */
Route::get('/exoneraciones/inicio', 'webController\ExoneracionesController@index')->name('exoneraciones.inicio')
    ->middleware('can:exoneraciones.inicio');
Route::get('/exoneraciones/agregar', 'webController\ExoneracionesController@create')->name('exoneraciones.agregar')
    ->middleware('can:exoneraciones.create');
Route::post('/exoneraciones/guardar', 'webController\ExoneracionesController@store')->name('exoneraciones.guardar')
    ->middleware('can:exoneraciones.store');
Route::get('/exoneraciones/edit/{id}', 'webController\ExoneracionesController@edit')->name('exoneraciones.edit')
    ->middleware('can:exoneraciones.edit');
Route::put('/exoneraciones/modificar/{id}', 'webController\ExoneracionesController@update')->name('exoneraciones.update')
    ->middleware('can:exoneraciones.update');
Route::post('/exoneraciones/sid/municipios', 'webController\ExoneracionesController@getmunicipios');

/*Reporte Financieros 03032021*/
Route::get('/financieros/reporte', 'webController\PagoController@financieros_reporte')->name('financieros.reporte')->middleware('can:financieros.reporte');
Route::post('/financieros/reporte/pdf','webController\PagoController@financieros_reportepdf')->name('financieros.reportepdf');
//Route::get('/reportes/arc01','pdfcontroller@arc')->name('pdf.generar');
Route::post('/reportes/arc01','pdfcontroller@arc')->name('pdf.generar');
Route::get('/reportes/vista_911','pdfcontroller@index')->name('reportes.vista_911');
Route::post('/reportes/vista_911','pdfcontroller@index')->name('reportes.vista_911');
Route::get('/reportes/vista_arc','pdfcontroller@index')->name('reportes.vista_arc');
Route::get('/reportes/vista_ft','ftcontroller@index')->name('vista_formatot')->middleware('can:vista.formatot.unidades.indice');
Route::post('/reportes/vista_ft','ftcontroller@cursos')->name('formatot.cursos');
Route::post('/reportes/vista_ft/savetodta', 'ftcontroller@store')->name('formatot.send.dta');
Route::post('/formato/ft/paso2', 'ftcontroller@paso2')->name('formatot.seguimiento.paso2');
Route::get('/validacion/cursos/index', 'Validacion\validacionDtaController@index')->name('validacion.cursos.enviados.dta')->middleware('can:vista.validacion.enlaces.dta');
Route::get('/validacion/dta/revision/cursos/index', 'Validacion\validacionDtaController@indexRevision')->name('validacion.dta.revision.cursos.indice')->middleware('can:vista.validacion.direccion.dta');
Route::post('/reportes/dta/savetounity', 'Validacion\validacionDtaController@storedtafile')->name('dta.send.unity');
// nueva modificación
Route::post('/validacion/cursos/', 'Validacion\validacionDtaController@store')->name('enviar.cursos.validacion.dta');
Route::any('/validacion/cursos/dta/envio', 'Validacion\validacionDtaController@storetodta')->name('validar.cursos.direccion.dta');
Route::post('/validacion/dta/revision/enviar/planeacion', 'ftcontroller@sendToPlaneacion')->name('formatot.send.planeacion');

//cancelacion de folio de supre 21/04/2021
Route::post('/supre/cancelacion/folio', 'webController\supreController@cancelFolio')->name('folio-cancel');

//Reporte alumnos vinculacion 31/05/2021
Route::get('/vinculadores/reporte-cursos', 'webController\CursoValidadoController@cursosVinculador_reporte')->name('cursosvinculador.reporte');
Route::post('/directorio/getvin','webController\CursoValidadoController@get_vin')->name('get-vin');
Route::post('/vinculacion/reporte/pdf','webController\CursoValidadoController@vinculacion_reportepdf')->name('vinculacion.reportepdf');
/**
 * apartado supre generado por ISC. Orlando Chavez 07/06/2021
 */
Route::get('planeacion/generar/reporte/{filtrotipo}/{idcurso}/{unidad}/{idInstructor}/{fecha1}/{fecha2}', 'webController\supreController@generate_report_supre_pdf')->name('planeacion.generar.reporte.supre.pdf');
Route::get('planeacion/generar/reporte/xls/{filtrotipo}/{idcurso}/{unidad}/{idInstructor}/{fecha1}/{fecha2}', 'webController\supreController@generate_report_supre_xls')->name('planeacion.generar.reporte.supre.xls');
