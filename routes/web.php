<?php

use App\Http\Controllers\LevelController;
use App\Http\Controllers\KategoriController;  
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DB;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/level', [LevelController::class, 'index']);
Route::get('/kategori', [KategoriController::class, 'index']);
