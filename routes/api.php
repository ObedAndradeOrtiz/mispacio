<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiTratamientos;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/porsucursal/{sucursal}', [ApiTratamientos::class, 'porsucursal']);
Route::get('/saldos/{sucursal}', [ApiTratamientos::class, 'saldos']);
Route::get('/inmuebles/{sucursal}', [ApiTratamientos::class, 'inmuebles']);
Route::get('/marketing/pagos', [ApiTratamientos::class, 'pagos']);
Route::get('/marketing/ingresos', [ApiTratamientos::class, 'ingresos']);
Route::post('/mensajes', [ApiTratamientos::class, 'mensajes']);
Route::get('/personal', [ApiTratamientos::class, 'GetPersonal']);
Route::post('/numeros', [ApiTratamientos::class, 'crearEmprendedor']);
