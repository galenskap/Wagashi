<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\Game;
use App\Models\Player;
use App\Jobs\CardsCreation;
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
     * @param  string  $cardpack
     * @return boolean
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
     * @param Request $request
     * @return json
     */
    public function isGameValid(Request $request)
    {
        // the middleware CheckGameLobby already checks if the game session exists and is valid
        return response()->json(true);
    }

    /**
     * Handle new game session (lobby) creation request
     *
     * @param Request $request
     * @return json
     */
    public function newGame(Request $request)
    {
        // Fields validation
        $request->validate([
            'cardpack' => 'required|string',
            'owner_pseudo' => 'required|string|min:1|max:40',
            'score_goal' => 'required|int|min:1|max:20'
        ]);

        // Create a new game
        $game = $this->createGame((int)$request->score_goal);
        // Create a new player
        $player = $this->createPlayer((string)$request->owner_pseudo, $game);

        // Then, make him lobby_owner
        $game->owner()->associate($player);
        $game->save();

        $cardpack = $this->isCardpackValid($request->cardpack) ? $request->cardpack : 'default';
        // Queue a job to generate cards
        CardsCreation::dispatch($game->id, $cardpack);


        // Return the game slug and player's token and player's id
        return response()->json([
            "slug" => $game->slug,
            "player_id" => $player->id,
            "token" => $player->token
        ]);
    }

    /**
     * Handle new player creation request
     *
     * @param  Request $request
     * @return json
     */
    public function newPlayer(Request $request)
    {
        // Validate fields
        $request->validate([
            'gameslug' => 'required|string|exists:games,slug',
            'pseudo' => 'required|min:1|max:40'
        ]);

        // Load the game
        $game = Game::where('slug', $request->gameslug)->first();
        // Create a new player
        $player = $this->createPlayer((string)$request->pseudo, $game);

        // Return the token
        return response()->json([
            'player' => $player->id,
            "token" => $player->token
        ]);
    }

    /**
     * Register a new game session (lobby)
     *
     * @param int $score_goal
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
     * @param string $pseudo
     * @param Game $game
     * @return Player
     */
    public function createPlayer($pseudo, $game)
    {
        // Create player
        $player = new Player;
        $player->pseudo = (string)$pseudo;
        $player->game()->associate($game);
        $player->current_score = 0;
        $player->save();

        // Create and return the player token
        $token = [
            'player' => $player->id,
            'game' => $game->id,
        ];
        $token = encrypt($token);

        $player->token = $token;
        $player->save();

        return $player;
    }


}
