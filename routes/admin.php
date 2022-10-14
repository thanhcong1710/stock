<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\CampaignsController;
use Illuminate\Support\Facades\Route;

Route::match(['get', 'post'], '/login', [LoginController::class, 'login'])->name('admin.login');
Route::middleware('auth:admin')->group(function (){
    Route::get('/', [HomeController::class, 'index'])->name('admin.dashboard');
    Route::get('/import', [HomeController::class, 'import'])->name('admin.import');
    Route::post('/import/process', [HomeController::class, 'importProcess'])->name('admin.import.process');
    Route::get('/campaign', [CampaignsController::class, 'list'])->name('admin.campaign.list');
    Route::get('/campaign/add', [CampaignsController::class, 'add'])->name('admin.campaign.add');
    Route::post('/campaign/save', [CampaignsController::class, 'save'])->name('admin.campaign.save');
    Route::get('/campaign/process/{campaign_id}', [CampaignsController::class, 'process'])->name('admin.campaign.process');
    Route::get('/campaign/process-data-history-month/{ma}', [CampaignsController::class, 'processDataHistoryMonth']);
    Route::get('/crawl/cophieu68', [HomeController::class, 'crawlCoPhieu68']);
    Route::get('/crawl/cophieu68-ma', [HomeController::class, 'crawlCoPhieu68Ma']);
});