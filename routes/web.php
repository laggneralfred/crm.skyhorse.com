<?php

use Illuminate\Support\Facades\Route;
// use Illuminate\Http\Request;
use App\Models\SolarProject;
use App\Http\Controllers\HubspotSyncController;
use Livewire\Livewire;
use App\Http\Livewire\SolarProjectViewer;
use App\Livewire\Settings\{Appearance, Password, Profile};
use App\Http\Controllers\OpenAIQueryController;

use Illuminate\Http\Request; // ✅ this import is okay

Route::get('/hubspot-handler', function (Request $request) {
    $envProjectId = $request->query('ENVProjectID'); // ✅ use injected $request
    if (!$envProjectId) {
        abort(400, 'Missing ENVProjectID');
    }

    $project = \App\Models\SolarProject::where('ENVProjectID', $envProjectId)->firstOrFail();
    return view('popup', compact('project'));
});


/* --------------------------------------------------------------------------
 | Public pages
 * --------------------------------------------------------------------------*/
Route::view('/', 'welcome')->name('home');
Route::get('/test-herd', fn () => 'Herd is working!');


Route::get('/openai-query', [OpenAIQueryController::class, 'index'])->name('openai.index');
Route::post('/openai-query', [OpenAIQueryController::class, 'generate'])->name('openai.generate');

/* --------------------------------------------------------------------------
 | HubSpot integration
 * --------------------------------------------------------------------------*/
// Route::post('/hubspot/update-popup-url', [HubspotSyncController::class, 'updatePopupUrl']);
Route::get('/hubspot/create-field',      [HubspotSyncController::class, 'createPopupUrlField']);
Route::get('/hubspot/delete-field',      [HubspotSyncController::class, 'deletePopupUrlField']);
Route::get('/hubspot/sync-california', [\App\Http\Controllers\HubspotSyncController::class, 'syncCaliforniaContacts']);

/* --------------------------------------------------------------------------
 | Popup and redirect helpers
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
 | Demo / viewer routes
 * --------------------------------------------------------------------------*/
Livewire::component('project-viewer', SolarProjectViewer::class);

Route::get('/project/{id}', function ($id) {
    $project = SolarProject::with(['projectContacts', 'keyCompanyContacts'])->findOrFail($id);
    return view('livewire.solar-project-viewer-two-tab', compact('project'));
});

/* --------------------------------------------------------------------------
 | Authenticated dashboard & settings
 * --------------------------------------------------------------------------*/
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile',    Profile::class)->name('settings.profile');
    Route::get('settings/password',   Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

});

require __DIR__.'/auth.php';
