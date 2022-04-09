<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

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
