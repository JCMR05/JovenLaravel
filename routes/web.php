<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/productos', function () {
    return view('productos');
});

Route::get('/condicion/{nota}', function ($nota) {
    return view('estructuras.condicional', compact('nota'));
});

Route::get('/switch/{dia}', function ($dia) {
    return view('estructuras.switch', compact('dia'));
});

Route::get('/foreach/{fruta}', function ($fruta) {
    $frutas = ["Manzana","Pera","Uvas","Papaya","Melon","Kiwi","Guayaba"];
    return view('estructuras.foreach', compact('frutas','fruta'));
});