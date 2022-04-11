<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Proposition extends Pivot
{

    protected $hidden = ['created_at', 'updated_at', 'answer_id', 'game_id'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'currentpropositions';


    // Relationships
    // One
    public function game()
    {
        return $this->belongsTo(Game::class);
    }
    public function player()
    {
        return $this->belongsTo(Player::class);
    }
    public function answer()
    {
        return $this->belongsTo(Answer::class);
    }
}
