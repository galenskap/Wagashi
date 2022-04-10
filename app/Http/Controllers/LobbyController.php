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
    public function isSlugValid(Request $request)
    {
        // Validate fields
        $request->validate([
            'gameslug' => 'required|string|exists:games,slug',
        ]);
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
        // TODO : queue this job (php 8.0)
        // CardsCreation::dispatch($game, $cardpack)->delay(now()->addMinutes(1));
        $this->cardsGeneration($game, $cardpack);

        // Return the game slug and player's token
        return response()->json([
            "slug" => $game->slug,
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

    /**
     * Generate cards for a given game
     * @param Game $game
     * @param string $cardpack
     *
     * @return void
     */
    public function cardsGeneration(Game $game, $cardpack)
    {
        // get corresponding files from storage dir
        $questions_txt = Storage::get('cardpacks/' . $cardpack . '/questions.txt');
        $answers_txt = Storage::get('cardpacks/' . $cardpack . '/answers.txt');
        // read txt file
        $questions = preg_split("/\r\n|\n|\r/", $questions_txt);
        $answers = preg_split("/\r\n|\n|\r/", $answers_txt);

        // Create questions and answers
        foreach ($questions as $key => $question) {
            if (strlen($question)) {
                $game->questions()->create([
                    'text' => $question,
                    'status' => 1,
                ]);
            }
        }

        foreach ($answers as $key => $answer) {
            if (strlen($answer)) {
                $game->answers()->create([
                    'text' => $answer,
                    'status' => 'pile',
                ]);
            }
        }


    }
}
