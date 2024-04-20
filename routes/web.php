<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TimeTableController;
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

Route::get('/clients_', function () {
    return Inertia::render('client');
})->middleware(['auth', 'verified'])->name('client');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::group([
        'prefix' => 'client',
        'as' => 'client.'], function () {
        Route::get('/', [ClientController::class, 'index'])->name('index');
        Route::get('{client:slug}', [ClientController::class, 'show'])->name('show');
        Route::post('/', [ClientController::class, 'store'])->name('store');
        Route::put('{client}', [ClientController::class, 'update'])->name('update');
        Route::patch('{client}/prioroty', [ClientController::class, 'setPriorory'])->name('prioroty');
        Route::patch('/ordering', [ClientController::class, 'setOrder'])->name('ordering');
        Route::delete('{client}', [ClientController::class, 'destroy'])->name('destroy');
    });

    Route::group([
        'prefix' => 'project',
        'as' => 'project.'], function () {
        Route::get('/', [ClientController::class, 'index'])->name('index');
        Route::get('{project}', [ProjectController::class, 'show'])->name('show');
        Route::post('/', [ProjectController::class, 'store'])->name('store');
        Route::put('{project}', [ProjectController::class, 'update'])->name('update');
        Route::patch('{project}/prioroty', [ProjectController::class, 'setPriorory'])->name('prioroty');
        Route::patch('/ordering', [ProjectController::class, 'setOrder'])->name('ordering');
        Route::delete('{project}', [ProjectController::class, 'destroy'])->name('destroy');
        Route::post('start-time/{project}', [ProjectController::class, 'startTime'])->name('startTime');
        Route::post('stop-time/{project}', [ProjectController::class, 'stopTime'])->name('stopTime');
    });

    Route::group([
        'prefix' => 'timeTable',
        'as' => 'timeTable.'], function () {
        Route::post('stop-time/{timeTable}', [TimeTableController::class, 'stopTime'])->name('stopTime');
        Route::delete('{timeTable}', [TimeTableController::class, 'destroy'])->name('destroy');
    });

    
});

require __DIR__.'/auth.php';
