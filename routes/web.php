<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServidorController;


Route::get('/servidores', [ServidorController::class, 'index'])->name('servidores.index'); 

Route::post('/servidores', [ServidorController::class, 'store'])->name('servidores.store');

Route::get('/', function () {
    return view('welcome');
});
