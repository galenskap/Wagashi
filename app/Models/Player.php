<?php

namespace App\Models;

use App\Models\Proposition;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Player extends Model
{
    use HasFactory;

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

        // TODO: check if there are enough cards left in the pile

        // Draw cards if the current player has less than 10 cards
        if ($nbCards < self::MAX_CARDS) {

            $cards = $this->game->answers()->where('status', 'pile')->inRandomOrder()->take(self::MAX_CARDS - $nbCards)->get();

            foreach($cards as $card) {
                $card->status = 'hand';
                $card->player()->associate($this);
                $card->save();
            }
        }

        // Return the player's cards
        return $this->answers;
    }
}
