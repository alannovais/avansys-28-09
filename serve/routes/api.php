<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OperadoraController;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::get('/operadora', [OperadoraController::class, 'index']);
Route::get('/operadora/{id}', [OperadoraController::class, 'show']);
Route::post('/operadora/create', [OperadoraController::class, 'store']);
Route::put('/operadora/update/{id}', [OperadoraController::class, 'update']);
Route::put('/operadora/delete/{id}', [OperadoraController::class, 'delete']);
