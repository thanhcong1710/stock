<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\HomeController;
use Illuminate\Support\Facades\Route;

Route::match(['get', 'post'], '/login', [LoginController::class, 'login'])->name('admin.login');
Route::middleware('auth:admin')->group(function (){
    Route::get('/', [HomeController::class, 'index'])->name('admin.dashboard');
    Route::get('/import', [HomeController::class, 'import'])->name('admin.import');
    Route::post('/import/process', [HomeController::class, 'importProcess'])->name('admin.import.process');
});