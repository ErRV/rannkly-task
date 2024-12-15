<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CampaignController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('/campaign-list', [CampaignController::class, 'campaignList'])->name('campaign.list');
    Route::get('/campaign-form', [CampaignController::class, 'campaignForm'])->name('campaign.form');
    Route::post('/campaign-form', [CampaignController::class, 'store'])->name('campaign.store');
    
    Route::get('/campaign/{id}', [CampaignController::class, 'viewCampaign'])->name('campaign.view');
    Route::get('/get-campaign-processing-count/{id}', [CampaignController::class, 'getCampaignProcessingCount'])->name('campaign.processing.count');
});

require __DIR__.'/auth.php';