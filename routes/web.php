<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TimeTableController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;

// Redirect to Google's OAuth
Route::get('/auth/google', function () {
    return Socialite::driver('google')->scopes(['https://www.googleapis.com/auth/drive', 'https://www.googleapis.com/auth/documents'])->redirect();
})->name('google.redirect');

// Handle Google's OAuth Callback
Route::get('/auth/google/callback', function () {
    $user = Socialite::driver('google')->stateless()->user();

    // Save the user's access token and refresh token
    session([
        'google_access_token' => $user->token,
        'google_refresh_token' => $user->refreshToken,
        'google_token_expires_in' => $user->expiresIn,
    ]);

    return redirect('/dashboard')->with('success', 'Google OAuth successful!');
});

Route::get('/google/refresh-token', function () {
    $refreshToken = session('google_refresh_token');
    if (!$refreshToken) {
        return redirect('/auth/google');
    }

    $client = new Google_Cl();
    $client->refreshToken($refreshToken);
    $newAccessToken = $client->getAccessToken();

    // Update the session
    session([
        'google_access_token' => $newAccessToken['access_token'],
        'google_token_expires_in' => $newAccessToken['expires_in'],
    ]);

    return redirect('/dashboard')->with('success', 'Token refreshed successfully!');
});

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

Route::middleware(['auth'])->group(function () {
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
        Route::patch('{client}/notes', [ClientController::class, 'updateNotes'])->name('updateNotes');
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
    });

    Route::group([
        'prefix' => 'timeTable',
        'as' => 'timeTable.'], function () {
        Route::post('stop-time/{timeTable}', [TimeTableController::class, 'stopTime'])->name('stopTime');
        Route::delete('{timeTable}', [TimeTableController::class, 'destroy'])->name('destroy');
    });
});

require __DIR__.'/auth.php';
