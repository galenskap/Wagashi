<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\Game;
use App\Models\Player;
use App\CustomHelper;

class LobbyController extends Controller
{
    /**
     * Fetch a list of all available cardpacks names
     *
     * @return json
     */
    public function getCardpacks()
    {
        // List all folders in storage folder
        $cardpacksFolders = Storage::directories('cardpacks');
        // Split the folder name to get the cardpack name
        foreach ($cardpacksFolders as $key => $folder) {
            $names[$key] = explode('/', $folder)[1];
        }
        return response()->json($names);
    }

    /**
     * Check if given cardpack really exists
     *
     * @return json
     */
    public function isCardpackValid($cardpack)
    {
        // Check if slug exists
        if (Storage::exists('cardpacks/' . $cardpack)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check if given slug matches with a game session
     *
     * @return json
     */
    public function isSlugValid($gameslug)
    {
        // Check if slug exists
        if (Game::where('slug', $gameslug)->exists()) {
            return response()->json(true);
        } else {
            return response()->json(false);
        }
    }

    /**
     * Handle new game session (lobby) creation request
     *
     * @return json
     */
    public function newGame(Request $request)
    {
        // Check if request contains all required fields
        // And do fields validation (pseudo length is at least 2 characters)
        if (!$request->has('cardpack') || !$request->has('owner_pseudo') || !strlen($request->owner_pseudo) > 2 || !$request->has('score_goal')) {
            // todo : return useful errors
            return response()->json(false);
        } else {
            // create a new game
            $game = $this->createGame((int)$request->score_goal);
            // create a new player
            $player = $this->createPlayer((string)$request->owner_pseudo, $game);

            // then, make him lobby_owner
            $game->owner()->associate($player);
            $game->save();

            // TODO : create cards form cardpack!
            // $this->isCardpackValid($request->cardpack) ? $request->cardpack : 'default';

            // Return the game slug
            return response()->json([
                "slug" => $game->slug,
                "game_id" => $game->id
            ]);
        }

    }

    /**
     * Handle new player creation request
     *
     * @return json
     */
    public function newPlayer(Request $request)
    {
        // Check if request contains all required fields
        // Do fields validation (pseudo length is at least 2 characters)
        // $player = $this->createPlayer((string)$request->owner_pseudo, $game->id);
    }

    /**
     * Register a new game session (lobby)
     * @return Game
     */
    public function createGame(int $score_goal)
    {
        // Create a new game
        $game = new Game;
        $game->score_goal = $score_goal;
        // Generate a random (but unique) slug
        do {
            $game->slug = CustomHelper::generateSlug();
        } while (Game::where('slug', $game->slug)->exists());
        $game->save();

        return $game;
    }

    /**
     * Register a new player
     * @return Player
     */
    public function createPlayer($pseudo, $game)
    {
        $player = new Player;
        $player->pseudo = (string)$pseudo;
        $player->game()->associate($game);
        $player->current_score = 0;
        $player->save();

        return $player;
    }
}
