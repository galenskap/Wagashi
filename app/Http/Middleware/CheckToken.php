<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Game;
use App\Models\Player;

class CheckToken
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
        $header = $request->header('Authorization');

        if(!$header) {
            return response()->json(array(
                'code' => 403,
                'message' => 'Token not provided',
            ), 403);
        }

        $token = decrypt(str_replace('Bearer ','',  $header));

        if(!$token['game'] || !$token['player']){
            return response()->json(array(
                'code' => 403,
                'message' => 'Wrong token',
            ), 403);
        }

        $game = Game::find($token['game']);
        $player = Player::find($token['player']);

        if (!$game || !$player) {
            return response()->json(array(
                'code' => 403,
                'message' => 'The token contains wrong data',
            ), 403);
        }

        $request->merge([
            'game' => $game,
            'player' => $player,
        ]);

        return $next($request);
    }
}
