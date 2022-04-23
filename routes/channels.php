<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });

Broadcast::channel('general-{gameId}', function ($player, $gameId) {
    return ($player->game_id == $gameId);
}, ['middleware' => ['token']]);

Broadcast::channel('player-{playerId}', function ($player, $playerId) {
    return ($player->id == $playerId);
}, ['middleware' => ['token']]);

Broadcast::channel('presence-{gameId}', function ($player, $gameId) {
    if ($player->game_id == $gameId) {
        return ['id' => $player->id, 'name' => $player->pseudo];
    }
}, ['middleware' => ['token']]);

// Use request token to check if the player belongs to the game
