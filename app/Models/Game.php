<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    // Relationships
    // One
    public function owner()
    {
        return $this->hasOne(Player::class, 'id', 'lobby_owner');
    }
    public function currentDealer()
    {
        return $this->hasOne(Player::class, 'id', 'current_dealer');
    }
    public function currentQuestion()
    {
        return $this->hasOne(Question::class, 'id', 'current_question');
    }

    // Many
    public function players()
    {
        return $this->hasMany(Player::class);
    }
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
    public function questions()
    {
        return $this->hasMany(Question::class);
    }
    public function propositions()
    {
        return $this->hasMany(Proposition::class);
    }


    // Use cases

    // get only available cards (= not discarded or already in hands)
    public function getAvailableQuestions()
    {
        return $this->questions()->where('status', true)->get();
    }
    public function getAvailableAnswers()
    {
        return $this->answers()->where('status', 'pile')->get();
    }



}
