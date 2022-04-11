<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Proposition;
use Illuminate\Http\Request;
use App\Jobs\CheckPropositions;
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

        return true;
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

    /**
     * RSend a player's proposition
     * @param Request $request
     */
    public function sendProposition(Request $request)
    {
        $player = $request->player;
        $game = $request->game;

        // Check how many propositions must be sent
        $nbProps = substr_count($game->currentQuestion->text, '##');

        // Validate fields
        $request->validate([
            'answers' => 'required|array|size:' . $nbProps,
        ]);

        // Check if the player is the current dealer
        if ($player == $game->currentDealer) {
            return response()->json(array(
                'code' => 403,
                'message' => 'You are the current dealer',
            ), 403);
        }

        // Check if player has already submitted something
        if ($player->propositions->count() > 0) {
            return response()->json(array(
                'code' => 403,
                'message' => 'You have already submitted a proposition',
            ), 403);
        }
        // Check if all submitted answers are really owned by the player
        foreach ($request->answers as $answer_id) {
            $answer = $player->answers()->find($answer_id);
            if (!$answer) {
                return response()->json(array(
                    'code' => 403,
                    'message' => 'You are not the owner of this answer',
                ), 403);
            }
        }

        // Register the propositions
        $order = 1;
        foreach ($request->answers as $answer) {
            $proposition = new Proposition();
            $proposition->order = $order;
            $proposition->answer()->associate($answer);
            $proposition->player()->associate($player);
            $proposition->game()->associate($game);
            $proposition->save();

            $order++;
        }

        // Check if all players have sent their propositions
        CheckPropositions::dispatch();
    }
}
