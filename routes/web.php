<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontOffice\AccueilController;
use App\Http\Controllers\UserController;

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
//
});

// Route accessible si l'utilisateur est authentifié et appartient à la catégorie assistante-maternelle
Route::middleware(['verified', 'assistante-maternelle'])->group(function () {
//
});