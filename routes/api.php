<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
// Check if given slug matches with a game session
Route::get('/check-slug', [LobbyController::class, 'isSlugValid']);
// Create a new game session (lobby)
Route::post('/new-game', [LobbyController::class, 'newGame']);
// Register a new user to the game session
Route::post('/new-player', [LobbyController::class, 'newPlayer']);


// GAMEPLAY
