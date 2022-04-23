<?php

namespace App\Models;

use App\Models\Proposition;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\Events\GeneralBroadcastNewPlayer;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Player extends Model
{
    use HasFactory;
    use Authenticatable;

    const MAX_CARDS = 10;

    protected $hidden = ['token', 'created_at', 'updated_at', 'game_id'];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'current_score' => 0, // default score
    ];

    // Relationships
    // One
    public function game()
    {
        return $this->belongsTo(Game::class);
    }
    public function ownedGame()
    {
        return $this->hasOne(Game::class, 'id', 'lobby_owner');
    }
    public function hasDeal()
    {
        return $this->hasOne(Game::class, 'id', 'current_dealer');
    }

    // Many
    public function answers()
    {
        return $this->hasMany(Answer::class, 'owner_id', 'id');
    }
    public function propositions()
    {
        return $this->hasMany(Proposition::class, 'player_id', 'id');
    }


    public function drawCards()
    {
        // Get current player number of cards
        $nbCards = $this->answers()->count();

        // Draw cards if the current player has less than 10 cards
        if ($nbCards < self::MAX_CARDS) {

            // Check if there are enough cards left in the pile
            $cardsLeft = $this->game->answers()->where('status', 'pile')->count();
            $cardsToDraw = self::MAX_CARDS - $nbCards;
            // if not, rebuild the pile of cards with the discarded ones
            if ($cardsLeft < $cardsToDraw) {
                $this->game->rebuildPile();
            }

            $cards = $this->game->answers()->where('status', 'pile')->inRandomOrder()->take($cardsToDraw)->get();

            foreach($cards as $card) {
                $card->status = 'hand';
                $card->player()->associate($this);
                $card->save();
            }
        }

        // Return the player's cards
        return $this->answers;
    }

    public function removePlayerFromGame()
    {
        // is he the last player of the game?
        if ($this->game->players->count() == 1) {
            // delete the game
            $this->game->delete();
        } else {
            // delete the player:
            // put his cards back in the pile
            $this->answers()->update(['owner_id' => null, 'status' => 'pile']);
            // delete the player
            $this->delete();

            // check if he is the lobby_owner?
            if ($this->game->lobby_owner == $this->id) {
                // if yes, set the next player as the lobby_owner
                $this->game->lobby_owner = $this->game->players->first()->id;
                $this->game->save();
            }

            // broadcast the players list to the general channel
            GeneralBroadcastNewPlayer::dispatch($this->game->id);
        }
    }
}
