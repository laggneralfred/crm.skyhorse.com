<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HubspotSyncController;

Route::post('/hubspot/update-popup-url',
    [HubspotSyncController::class, 'updatePopupUrl']);


Route::post('/hubspot/create-url-field',
    [HubspotSyncController::class, 'createPopupUrlField']);

Route::post('/hubspot/add-note', [HubspotSyncController::class, 'addNoteToContact']);
