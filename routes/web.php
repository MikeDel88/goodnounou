<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontOffice\AccueilController;
use App\Http\Controllers\AssistantesMaternelleController;
use App\Http\Controllers\RechercheController;
use App\Http\Controllers\EnfantController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ContratController;


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

// Route général du homepage
Route::get('/', [AccueilController::class, 'index'])->name('home');

// Menu général si l'utilisateur est authentifié et vérifier par email
Route::get('profile', [UserController::class, 'index'])->middleware(['auth','verified'])->name('profile');

// Cela crée des routes prédéfinies CRUD pour les utilisateurs
Route::resource('users', UserController::Class)->middleware(['auth', 'verified']);

// Route accessible si l'utilisateur est authentifié et appartient à la catégorie parents
Route::middleware(['verified', 'parents'])->group(function () {
    Route::name('parent.')->group(function(){
        Route::get('liste/enfants', [EnfantController::class, 'index'])->name('enfants');
        Route::post('liste/enfants', [EnfantController::class, 'store']);
        Route::get('/fiche/enfant/{id}', [EnfantController::class, 'edit']);
        Route::delete('/fiche/enfant/{id}', [EnfantController::class, 'destroy']);
        Route::put('/fiche/enfant/{id}', [EnfantController::class, 'update']);
        Route::get('recherche', [RechercheController::class, 'index'])->name('recherche');
        Route::post('/contrats/creation', [ContratController::class, 'store'])->name('contrat_creation');
    });   
});

// Route accessible si l'utilisateur est authentifié et appartient à la catégorie assistante-maternelle
Route::middleware(['verified', 'assistante-maternelle'])->group(function () {
    Route::name('assistante-maternelle.')->group(function(){
        Route::get('fiche/{id}', [AssistantesMaternelleController::class, 'editCard'])->name('fiche');
        Route::post('fiche/{id}', [AssistantesMaternelleController::class, 'updateCard']);
    });
});

// Route accessible pour un utilisateur vérifié
Route::middleware(['verified'])->group(function () {
    Route::get('fiche/assistante-maternelle/{id}', [AssistantesMaternelleController::class, 'showCard']);
    Route::get('contrats', [ContratController::class, 'index'])->name('contrats');
});

