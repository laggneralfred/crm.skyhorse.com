<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\SolarProject;
use App\Http\Controllers\HubspotSyncController;
use App\Http\Controllers\OpenAIQueryController;
use App\Http\Controllers\ProfileController; // ✅ Breeze profile controller
use App\Livewire\Settings\{Appearance, Password, Profile};
use Livewire\Livewire;
use App\Http\Livewire\SolarProjectViewer;

/* --------------------------------------------------------------------------
 | Homepage and Testing
 * --------------------------------------------------------------------------*/
Route::view('/', 'welcome')->name('home');
Route::get('/test-herd', fn () => 'Herd is working!');

/* --------------------------------------------------------------------------
 | Popup + Hubspot Handler
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
 | OpenAI Query Tool
 * --------------------------------------------------------------------------*/
Route::get('/openai-query', [OpenAIQueryController::class, 'index'])->name('openai.index');
Route::post('/openai-query', [OpenAIQueryController::class, 'generate'])->name('openai.generate');

/* --------------------------------------------------------------------------
 | HubSpot
 * --------------------------------------------------------------------------*/
Route::get('/hubspot/create-field', [HubspotSyncController::class, 'createPopupUrlField']);
Route::get('/hubspot/delete-field', [HubspotSyncController::class, 'deletePopupUrlField']);
Route::get('/hubspot/sync-california', [HubspotSyncController::class, 'syncCaliforniaContacts']);

/* --------------------------------------------------------------------------
 | Demo & Viewer Routes
 * --------------------------------------------------------------------------*/
Livewire::component('project-viewer', SolarProjectViewer::class);

Route::get('/project/{id}', function ($id) {
    $project = SolarProject::with(['projectContacts', 'keyCompanyContacts'])->findOrFail($id);
    return view('livewire.solar-project-viewer-two-tab', compact('project'));
});

/* --------------------------------------------------------------------------
 | Dashboard & Authenticated Pages
 * --------------------------------------------------------------------------*/
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    // Query Dashboard Routes
    Route::get('/query-dashboard', [OpenAIQueryController::class, 'dashboard'])
        ->name('query.dashboard');

    Route::post('/query-dashboard', [OpenAIQueryController::class, 'generateDashboard'])
        ->name('query.dashboard.generate');

    // User Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});Route::middleware(['auth'])->group(function () {
    // Livewire settings pages
    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile',    Profile::class)->name('settings.profile');
    Route::get('settings/password',   Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    // ✅ Breeze profile routes for navigation menu to work
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/* --------------------------------------------------------------------------
 | Breeze Auth
 * --------------------------------------------------------------------------*/
require __DIR__.'/auth.php';
