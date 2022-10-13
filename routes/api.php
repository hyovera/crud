<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MaiController;
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
Route::get('EmpleosActivos/{id}', [AuthController::class, 'EmpleosActivos']);

Route::get('VerEmpleos', [AuthController::class, 'VerEmpleos']);

Route::post('VerEmpleosPostulantes', [
    AuthController::class,
    'VerEmpleosPostulantes',
]);

Route::post('RegistroEmpleo', [AuthController::class, 'RegistroEmpleo']);
Route::post('ActualizarEmpleo', [AuthController::class, 'ActualizarEmpleo']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
