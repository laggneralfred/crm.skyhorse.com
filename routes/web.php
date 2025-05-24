<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\SolarProject;
use App\Http\Controllers\HubspotSyncController;
use App\Http\Controllers\OpenAIQueryController;
use App\Http\Controllers\ProfileController;
use App\Livewire\Settings\{Appearance, Password, Profile};
use Livewire\Livewire;
use App\Http\Livewire\SolarProjectViewer;

/* --------------------------------------------------------------------------
 | Public Pages
 * --------------------------------------------------------------------------*/
Route::view('/', 'welcome')->name('home');
Route::get('/test-herd', fn () => 'Herd is working!');

/* --------------------------------------------------------------------------
 | OpenAI Query Tool (Unauthenticated)
 * --------------------------------------------------------------------------*/
Route::get('/openai-query', [OpenAIQueryController::class, 'index'])->name('openai.index');
Route::post('/openai-query', [OpenAIQueryController::class, 'generate'])->name('openai.generate');

/* --------------------------------------------------------------------------
 | Popup Viewer for HubSpot integration
 * --------------------------------------------------------------------------*/
Route::get('/project/popup', function (Request $request) {
    $envProjectId = $request->query('ENVProjectID');
    abort_if(!$envProjectId, 400, 'Missing ENVProjectID');

    $project = SolarProject::where('ENVProjectID', $envProjectId)->firstOrFail();
    return view('popup', compact('project'));
});

Route::get('/hubspot-handler', function (Request $request) {
    $envProjectId = $request->query('ENVProjectID');
    abort_if(!$envProjectId, 400, 'Missing ENVProjectID');

    return redirect('/project/popup?ENVProjectID=' . urlencode($envProjectId));
});

/* --------------------------------------------------------------------------
 | HubSpot Field and Sync Utilities
 * --------------------------------------------------------------------------*/
Route::get('/hubspot/create-field', [HubspotSyncController::class, 'createPopupUrlField']);
Route::get('/hubspot/delete-field', [HubspotSyncController::class, 'deletePopupUrlField']);
Route::get('/hubspot/sync-california', [HubspotSyncController::class, 'syncCaliforniaContacts']);

/* --------------------------------------------------------------------------
 | Solar Project Viewer (Livewire)
 * --------------------------------------------------------------------------*/
Livewire::component('project-viewer', SolarProjectViewer::class);
Route::get('/project/{id}', function ($id) {
    $project = SolarProject::with(['projectContacts', 'keyCompanyContacts'])->findOrFail($id);
    return view('livewire.solar-project-viewer-two-tab', compact('project'));
});

/* --------------------------------------------------------------------------
 | Authenticated Area (Dashboard & Settings)
 * --------------------------------------------------------------------------*/
Route::view('/dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    // OpenAI Query Dashboard
    Route::get('/query-dashboard', [OpenAIQueryController::class, 'dashboard'])->name('query.dashboard');
    Route::post('/query-dashboard', [OpenAIQueryController::class, 'generateDashboard'])->name('query.dashboard.generate');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Livewire Settings Pages (optional)
    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

/* --------------------------------------------------------------------------
 | Breeze Email Verification & Auth
 * --------------------------------------------------------------------------*/
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

Route::middleware(['auth'])->group(function () {
    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->name('verification.send');

    Route::get('/email/verify', [VerifyEmailController::class, '__invoke'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
        ->middleware(['signed'])
        ->name('verification.verify');
});

require __DIR__.'/auth.php';
