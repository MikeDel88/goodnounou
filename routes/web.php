<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontOffice\ControllerAccueil;

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

Route::get('/', [ControllerAccueil::class, 'index']);

Route::get('profile', function () {
    $data['title'] = 'profile';
    return view('profil', $data);
})->middleware(['verified'])->name('profile');
