<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssistantesMaternellesAPI;
use App\Http\Controllers\RechercheAPI;
use App\Http\Controllers\CritereAPI;
use App\Http\Controllers\FavorisAPI;



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

Route::middleware('auth:api')->get('/user', function () {
    return response()->json([
        'status' => 'ok'
    ]);
});

Route::get('/assistante-maternelle/fiche/{id}', [AssistantesMaternellesAPI::class, 'show']);
Route::put('/assistante-maternelle/fiche/{id}', [AssistantesMaternellesAPI::class, 'update']);
Route::delete('/assistante-maternelle/fiche/{id}', [AssistantesMaternellesAPI::class, 'update']);

Route::put('/assistante-maternelle/critere/{id}', [CritereAPI::class, 'update']);
Route::delete('/assistante-maternelle/critere/{id}', [CritereAPI::class, 'update']);

Route::post('/recherche', [RechercheAPI::class, 'show']);

Route::post('/favoris', [FavorisAPI::class, 'update']);



