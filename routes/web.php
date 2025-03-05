<?php

use App\Http\Controllers\EmployesController;
use App\Http\Controllers\SuperviseurController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([AdminMiddleware::class])->group(function(){
    //presence
Route::get('/presentday', [SuperviseurController::class, 'dayPresentVue'])->name('route.presentday.list');
Route::get('/absenceday', [SuperviseurController::class, 'AbsentVue'])->name('route.absence.list');
//employés
Route::get('/adminAjoutEmploye', [SuperviseurController::class, 'ajoutEmployeVue'])->name('route.ajout.employe');
Route::get('/adminListeEmploye', [SuperviseurController::class, 'listeEmployeVue'])->name('route.liste.employes');
Route::post('/adminAjoutEmployeAction', [SuperviseurController::class, 'createEmployer'])->name('action.ajout.employe');
//departements
Route::get('/adminAjoutDepartement', [SuperviseurController::class, 'ajoutDepartementVue'])->name('route.ajout.departement');
Route::get('/adminListeDepartement', [SuperviseurController::class, 'listeDepartementVue'])->name('route.liste.departement');
Route::post('/adminDepartementAction', [SuperviseurController::class, 'createDepartement'])->name('ajout.departement.action');
//dashboard
Route::get('/dashadmin', [SuperviseurController::class, 'index'])->name('route.dash.page');
//logout
Route::get('/logoutAdmin', [SuperviseurController::class, 'logout'])->name('deconnexion.superviseur');
});


//login
Route::get('/loginpage', [SuperviseurController::class, 'loginvue'])->name('route.login.page');
Route::post('/loginpageaction', [SuperviseurController::class, 'loginRequest'])->name('route.login.action');
//qr-verification
Route::post('/verify-qr', [EmployesController::class, 'verifyQrCode']);

