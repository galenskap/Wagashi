<?php

use App\Models\Game;
use Illuminate\Support\Facades\Route;
use App\Events\GeneralBroadcastQuestion;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('base');
});


Route::fallback(function () {
    return view('base');
});

Route::get('/test', function () {

    $game = Game::find(11);
    GeneralBroadcastQuestion::dispatch($game, "Quelle est ## truc ?", 45);
    echo "sent";
});

Route::post('login', [ 'as' => 'login'] , function () {
    return true;
});
