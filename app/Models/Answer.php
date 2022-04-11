<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = ['text', 'status', 'owner_id'];
    protected $hidden = ['created_at', 'updated_at', 'status', 'owner_id', 'game_id'];



    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => 'pile', // default = in pile (available to draw)
    ];


    // Relationships
    // One
    public function game()
    {
        return $this->belongsTo(Game::class);
    }
    public function player()
    {
        return $this->belongsTo(Player::class, 'owner_id');
    }
}
