<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

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
}
