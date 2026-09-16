<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Legatra\TSP\RequestDocumentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get(
    '/tsp/request-document/sla-notification',
    [RequestDocumentController::class, 'slaNotification']
);

Route::get(
    '/tsp/request-document/expiration-notification',
    [RequestDocumentController::class, 'expirationNotification']
);
