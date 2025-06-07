<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\SolarProject;
use App\Http\Controllers\{
    HubspotSyncController,
    OpenAIQueryController,
    ProfileController
};
use App\Livewire\Settings\{Appearance, Password, Profile};
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Livewire\Livewire;

/* --------------------------------------------------------------------------
 | Public Pages
 * --------------------------------------------------------------------------*/
Route::view('/', 'welcome')->name('home');
Route::get('/test-herd', fn () => 'Herd is working!');

/* --------------------------------------------------------------------------
 | OpenAI Query Tool (Public Version)
 * --------------------------------------------------------------------------*/
Route::get('/openai-query', [OpenAIQueryController::class, 'index'])->name('openai.index');
Route::post('/openai-query', [OpenAIQueryController::class, 'generate'])->name('openai.generate');

/* --------------------------------------------------------------------------
 | Popup Viewer for HubSpot
 * --------------------------------------------------------------------------*/
Route::get('/project/popup', function (Request $request) {
    $envProjectId = $request->query('ENVProjectID');
    abort_if(!$envProjectId, 400, 'Missing ENVProjectID');

    $project = SolarProject::where('ENVProjectID', $envProjectId)->firstOrFail();
    return view('popup', compact('project'));
});

Route::get('/hubspot-handler', fn (Request $request) => redirect('/project/popup?ENVProjectID=' . urlencode($request->query('ENVProjectID'))))
    ->middleware('throttle:5,1');

/* --------------------------------------------------------------------------
 | HubSpot Sync Utilities
 * --------------------------------------------------------------------------*/
Route::get('/hubspot/create-field', [HubspotSyncController::class, 'createPopupUrlField']);
Route::get('/hubspot/delete-field', [HubspotSyncController::class, 'deletePopupUrlField']);
Route::get('/hubspot/sync-california', [HubspotSyncController::class, 'syncCaliforniaContacts']);

/* --------------------------------------------------------------------------
 | Livewire Solar Project Viewer
 * --------------------------------------------------------------------------*/
Livewire::component('project-viewer', \App\Livewire\SolarProjectViewer::class);

Route::get('/project/{id}', function ($id) {
    $project = SolarProject::with(['projectContacts', 'keyCompanyContacts'])->findOrFail($id);
    return view('livewire.solar-project-viewer-two-tab', compact('project'));
});

/* --------------------------------------------------------------------------
 | Authenticated Dashboard & Settings
 * --------------------------------------------------------------------------*/
Route::middleware(['auth', 'verified'])->group(function () {
    //Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::get('/dashboard', [OpenAIQueryController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');
    // Profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Settings (Livewire)
    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    // Query Dashboard (OpenAI)
    Route::get('/query-dashboard', [OpenAIQueryController::class, 'dashboard'])->name('query.dashboard');
    Route::post('/query-dashboard', [OpenAIQueryController::class, 'generate'])->name('query.generate'); // OR alias this one
    Route::post('/query-dashboard/generate', [OpenAIQueryController::class, 'generate']); // optional fallback
    Route::any('/query-dashboard/run/{query}', [OpenAIQueryController::class, 'runStoredQuery'])->name('query.run');
    Route::any('/query-dashboard/delete/{query}', [OpenAIQueryController::class, 'deleteQuery'])->name('query.delete');

    Route::post('/query-dashboard/clear', [OpenAIQueryController::class, 'clearQueries'])->name('query.clear');
  //  Route::any('/query-dashboard/favorite/{query}', [OpenAIQueryController::class, 'favorite'])->name('query.favorite');Route::post('/query-dashboard/favorite/{query}', [OpenAIQueryController::class, 'favorite'])->name('query.favorite');
    Route::post('/queries/{query}/favorite', [OpenAIQueryController::class, 'favorite'])->name('query.favorite');
    Route::post('/query-dashboard/favorite/{query}', [OpenAIQueryController::class, 'favorite'])->name('query.favorite');


});

/* --------------------------------------------------------------------------
 | Auth: Breeze Email Verification + Password
 * --------------------------------------------------------------------------*/
Route::middleware(['auth'])->group(function () {
    Route::put('/password', [\App\Http\Controllers\Auth\PasswordController::class, 'update'])->name('password.update');
    Route::post('/email/verification-notification', [\App\Http\Controllers\Auth\EmailVerificationNotificationController::class, 'store'])
        ->name('verification.send');
    Route::get('/email/verify', [\App\Http\Controllers\Auth\VerifyEmailController::class, '__invoke'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [\App\Http\Controllers\Auth\VerifyEmailController::class, '__invoke'])
        ->middleware(['signed'])
        ->name('verification.verify');
});
/* --------------------------------------------------------------------------
 | Auth Scaffolding (from Breeze)
 * --------------------------------------------------------------------------*/
require __DIR__.'/auth.php';
