<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontOffice\AccueilController;
use App\Http\Controllers\AssistantesMaternelleController;
use App\Http\Controllers\RechercheController;
use App\Http\Controllers\EnfantController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ContratController;
use App\Http\Controllers\FavorisController;
use App\Http\Controllers\HorairesController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\MessagesController;



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
        Route::get('/contrat/{id}/supprimer', [ContratController::class, 'destroy'])->name('contrat_supprimer');
        Route::get('/contrat/{id}/editer', [ContratController::class, 'edit'])->name('contrat_edit');
        Route::get('/favoris', [FavorisController::class, 'show'])->name('favoris');
        Route::post('/horaires/ajouter', [HorairesController::class, 'store']);
        Route::get('/contrat/{id}/cloture', [ContratController::class, 'clos']);
        Route::get('/carnet-de-bord/consulter', [MessagesController::class, 'show'])->name('carnet_consultation');
    });   
});

// Route accessible si l'utilisateur est authentifié et appartient à la catégorie assistante-maternelle
Route::middleware(['verified', 'assistante-maternelle'])->group(function () {
    Route::name('assistante-maternelle.')->group(function(){
        Route::get('fiche/{id}', [AssistantesMaternelleController::class, 'editCard'])->name('fiche');
        Route::post('fiche/{id}', [AssistantesMaternelleController::class, 'updateCard']);
        Route::get('/contrat/{id}/validation', [ContratController::class, 'validation'])->name('contrat_validation');
        Route::get('/contrat/{id}/refus', [ContratController::class, 'refus'])->name('contrat_refus');
        Route::get('/contrat/{id}', [ContratController::class, 'show'])->name('contrat_show');
        Route::get('/carnet-de-bord', [MessagesController::class, 'create'])->name('carnet');
        Route::post('/message/ajouter', [MessagesController::class, 'store']);
        Route::post('/message/modifier', [MessagesController::class, 'update']);
    });
});

// Route accessible pour un utilisateur vérifié
Route::middleware(['verified'])->group(function () {
    Route::get('fiche/assistante-maternelle/{id}', [AssistantesMaternelleController::class, 'showCard']);
    Route::get('contrats', [ContratController::class, 'index'])->name('contrats');
});


// Génère un PDF pour les horaires de garde
Route::get('pdf/horaires/{contrat}/{mois}/{annee}', [PDFController::class, 'generatePDF']);

