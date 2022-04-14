<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\GameController;
use App\Http\Controllers\LobbyController;

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

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
 */

// LOBBY

// Fetch a list of all available cardpacks names
Route::get('/cardpacks', [LobbyController::class, 'getCardpacks']);
// Create a new game session (lobby)
Route::post('/new-game', [LobbyController::class, 'newGame']);

Route::group(['middleware' => 'lobby'], function () {
    // Check if given slug matches with a valid game session (neither already full nor finished)
    Route::post('/check-game', [LobbyController::class, 'isGameValid']);
    // Register a new user to the game session
    Route::post('/new-player', [LobbyController::class, 'newPlayer']);
});


// GAMEPLAY

// from now on you must be registered to the game session
Route::group(['middleware' => 'token'], function () {
    Route::get('/get-data', [GameController::class, 'getGameData']);
    Route::get('/launch-game', [GameController::class, 'launchGame']);
    Route::post('/send-proposition', [GameController::class, 'sendProposition']);
    Route::post('/send-dealer-choice', [GameController::class, 'sendDealerChoice']);
});

