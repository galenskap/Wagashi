<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Game;

class CheckGameLobby
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        // Validate fields
        $request->validate([
            'gameslug' => 'required|string|exists:games,slug',
        ]);

        $game = Game::where('slug', $request->gameslug)->first();

        // Check if game is full (10 players maximum)
        if ($game->players()->count() >= Game::MAX_PLAYERS) {
            return response()->json(array(
                'code' => 422,
                'message' => 'Game is full',
            ), 422);
        }

        // Check if game is finished (player with maximum score)
        if ($game->players()->max('current_score') >= $game->score_goal) {
            return response()->json(array(
                'code' => 422,
                'message' => 'Game is finished',
            ), 422);
        }

        return $next($request);
    }
}
