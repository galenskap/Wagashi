<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['text', 'status'];
    protected $hidden = ['created_at', 'updated_at', 'status', 'game_id'];



    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => true, // default = in pile (available to draw)
    ];


    // Relationships
    // One
    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
