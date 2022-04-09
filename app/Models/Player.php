<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    // Relationships
    // One
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    // Many
    public function answers()
    {
        return $this->hasMany(Answer::class, 'owner_id', 'id');
    }
}
