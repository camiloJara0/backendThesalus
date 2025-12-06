<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
use App\Http\Controllers\EpsController;
use App\Http\Controllers\ProfesionController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfesionalController;
use App\Http\Controllers\SeccionesController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\HistoriaClinicaController;
use App\Http\Controllers\AnalisisController;
use App\Http\Controllers\ExamenFisicoController;
use App\Http\Controllers\PlanManejoMedicamentoController;
use App\Http\Controllers\PlanManejoProcedimientoController;
use App\Http\Controllers\PlanManejoEquipoController;
use App\Http\Controllers\PlanManejoInsumoController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\AntecedenteController;
use App\Http\Controllers\DiagnosticoController;
use App\Http\Controllers\DiagnosticoRelacionadoController;
use App\Http\Controllers\NotaController;
use App\Http\Controllers\DescripcionNotaController;
use App\Http\Controllers\SoftwareController;
use App\Http\Controllers\FacturacionController;
use App\Http\Controllers\EnfermedadController;
use App\Http\Controllers\InformacionUserController;
use App\Http\Controllers\TerapiaController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\Cie10Controller;

Route::post('/v1/login', [UserController::class, 'login']);
Route::post('/v1/recuperarContraseña', [UserController::class, 'verificacion']);
Route::post('/v1/cambiarContraseña', [UserController::class, 'verificarCodigo']);
Route::post('/v1/primerIngreso', [UserController::class, 'verificarUsuario']);
Route::get('/v1/sello/{filename}', [ProfesionalController::class, 'obtenerSelloBase64']);

Route::middleware(['auth:sanctum', 'check.token.expiration'])->group(function () {
        Route::apiResource('/v1/eps', EpsController::class);
        Route::apiResource('/v1/professions', ProfesionController::class);
        Route::apiResource('/v1/empresas', EmpresaController::class);
        Route::apiResource('/v1/users', UserController::class);
        Route::apiResource('/v1/profesionals', ProfesionalController::class);
        Route::apiResource('/v1/pacientes', PacienteController::class);
        Route::apiResource('/v1/informacionUsers', InformacionUserController::class);
        Route::apiResource('/v1/historiasClinicas', HistoriaClinicaController::class);
        Route::post('/v1/historiasClinicasNutricion', [HistoriaClinicaController::class, 'storeNutricion']);
        Route::post('/v1/historiasClinicasTrabajoSocial', [HistoriaClinicaController::class, 'storeTrabajoSocial']);
        Route::post('/v1/historiasClinicasNota', [HistoriaClinicaController::class, 'storeNota']);
        Route::apiResource('/v1/analisis', AnalisisController::class);
        Route::apiResource('/v1/examenFisicos', ExamenFisicoController::class);
        Route::apiResource('/v1/planManejoMedicamentos', PlanManejoMedicamentoController::class);
        Route::apiResource('/v1/planManejoProcedimientos', PlanManejoProcedimientoController::class);
        Route::post('/v1/diasAsignadosRestantes', [PlanManejoProcedimientoController::class, 'diasAsignadosRestantes']);
        Route::get('/v1/administradores', [UserController::class, 'administradores']);
        Route::apiResource('/v1/planManejoEquipos', PlanManejoEquipoController::class);
        Route::apiResource('/v1/planManejoInsumos', PlanManejoInsumoController::class);
        Route::apiResource('/v1/citas', CitaController::class);
        Route::apiResource('/v1/antecedentes', AntecedenteController::class);
        Route::apiResource('/v1/diagnosticos', DiagnosticoController::class);
        Route::apiResource('/v1/diagnosticosCIF', DiagnosticoRelacionadoController::class);
        Route::apiResource('/v1/notas', NotaController::class);
        Route::apiResource('/v1/descripcionNotas', DescripcionNotaController::class);
        Route::apiResource('/v1/software', SoftwareController::class);
        Route::apiResource('/v1/facturaciones', FacturacionController::class);
        Route::apiResource('/v1/enfermedades', EnfermedadController::class);
        Route::apiResource('/v1/terapias', TerapiaController::class);
        Route::apiResource('/v1/servicios', ServicioController::class);
        Route::apiResource('/v1/cie10', Cie10Controller::class);
        Route::get('/v1/profesional/document/{no_document}', [ProfesionalController::class, 'showPorDocumento']);
        Route::get('/v1/secciones', [SeccionesController::class, 'index']);
});