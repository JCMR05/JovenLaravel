<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Auth\PerfilController;
use App\Http\Controllers\Auth\RegisterController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {

    Route::resource('usuarios', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::patch('usuarios/{usuario}/toggle', [UserController::class, 'toggleStatus'])->name('usuarios.toggle');
    Route::get('dashboard', function () { return view('dashboard');})->name('dashboard');
    //segunda parte logout
    Route::post('logout', function (){
     Auth::logout();
     return redirect('/login');
    })->name('logout');

    Route::get('/perfil', [PerfilController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil', [PerfilController::class, 'update'])->name('perfil.update');
});

Route::middleware('guest')->group(function () {
    Route::get('login', function () { return view('autenticacion.login');})->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');


    Route::get('/registro', [RegisterController::class, 'showRegistrationForm'])->name('registro');
    Route::post('/registro', [RegisterController::class, 'registration'])->name('registro.store');
});