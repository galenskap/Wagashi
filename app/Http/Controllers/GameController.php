<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GameController extends Controller
{
    public function launchGame(Request $request)
    {
        $game = $request->game;
        // Check if the player requesting is the owner of the game
        if ($request->player->id !== $game->lobby_owner) {
            return response()->json(array(
                'code' => 403,
                'message' => 'You are not the owner of this game',
            ), 403);
        }

        // Check if there are at least 3 players
        if ($game->players()->count() < Game::MIN_PLAYERS) {
            return response()->json(array(
                'code' => 422,
                'message' => 'Not enough players',
            ), 422);
        }

        // Check if the game is already launched
        if ($game->current_dealer !== null) {
            return response()->json(array(
                'code' => 403,
                'message' => 'This game has already been launched',
            ), 403);
        }

        // Draw random 10 answer cards for each player
        $players = $game->players;
        foreach ($players as $player) {
            // Draw cards
            $playerCards = $player->drawCards();

            // TODO : Broadcast cards to player
        }

        // Draw a question card
        $questionCard = $game->drawQuestionCard();

        // Draw a dealer
        $dealer = $game->drawDealer();

        // TODO: Broadcast question card and dealer to players


    }

    /**
     * Return the global game data to the frontend
     */
    public function getGameData(Request $request)
    {
        $player = $request->player->load('answers');
        $game = $request->game->load('players', 'currentQuestion', 'currentDealer', 'propositions');

        return response()->json([
            'player' => $player,
            'game' => $game,
        ]);



    }
}
