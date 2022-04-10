<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    const MAX_PLAYERS = 10;
    const MIN_PLAYERS = 3;

    protected $hidden = ['created_at', 'updated_at'];


    // Relationships
    // One
    public function owner()
    {
        return $this->belongsTo(Player::class, 'lobby_owner');
    }
    public function currentDealer()
    {
        return $this->belongsTo(Player::class, 'current_dealer');
    }
    public function currentQuestion()
    {
        return $this->belongsTo(Question::class, 'current_question');
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

    /**
     * Draw a question card and
     *
     * @return Question
     *
     */
    public function drawQuestionCard()
    {
        $question = $this->getAvailableQuestions()->random();
        $question->status = false;
        $this->currentQuestion()->associate($question);

        $question->save();
        $this->save();

        return $question;
    }

    /**
     * Draw a dealer
     *
     * @return Player
     *
     */
    public function drawDealer()
    {
        if($this->current_dealer == null) {
            $dealer = $this->players()->inRandomOrder()->first();
            $this->currentDealer()->associate($dealer);
        } else {
            // Get the next player by id, if the current dealer is the last player, get the first one
            $dealer = $this->players()->where('id', '>', $this->current_dealer)->first();
            if($dealer == null) {
                $dealer = $this->players()->first();
            }
            $this->currentDealer()->associate($dealer);

        }

        $this->save();

        return $dealer;
    }


}
