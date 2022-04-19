<?php

namespace App\Models;

use App\Events\PlayerCards;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    /**
     * Draw each player's cards
     */
    public function drawPlayersCards()
    {
        $players = $this->players;
        foreach ($players as $player) {
            // Draw cards
            $playerCards = $player->drawCards();

            // Broadcast cards to player
            PlayerCards::dispatch($player->id);
        }
    }


     /**
     * Generate cards for a given game
     * @param string $cardpack
     *
     * @return void
     */
    public function cardsGeneration($cardpack)
    {
        // get corresponding files from storage dir
        $questions_txt = Storage::get('cardpacks/' . $cardpack . '/questions.txt');
        $answers_txt = Storage::get('cardpacks/' . $cardpack . '/answers.txt');
        // read txt file
        $questions = preg_split("/\r\n|\n|\r/", $questions_txt);
        $answers = preg_split("/\r\n|\n|\r/", $answers_txt);

        // Create questions and answers
        foreach ($questions as $key => $question) {
            if (strlen($question)) {
                $this->questions()->create([
                    'text' => $question,
                    'status' => 1,
                ]);
            }
        }

        foreach ($answers as $key => $answer) {
            if (strlen($answer)) {
                $this->answers()->create([
                    'text' => $answer,
                    'status' => 'pile',
                ]);
            }
        }

    }

    /**
     * Check how many players have sent their propositions
     *
     * @return boolean
     */
    public function checkNumberOfPropositions()
    {
        $nbPlayerHavingSentPropositions = DB::table('currentpropositions')->where('game_id', $this->id)->get()->groupBy('player_id')->count();
        return $nbPlayerHavingSentPropositions;
    }

     /**
     * Check if all players have sent their propositions
     *
     * @return boolean
     */
    public function areAllPropositionsSent()
    {
        $nbPlayerHavingSentPropositions = $this->checkNumberOfPropositions();

        if ($nbPlayerHavingSentPropositions == ($this->players()->count() - 1)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get all the propositions for the current game, grouped by player
     * @return array
     */
    public function getAllPropositions()
    {
        $propositions = DB::table('currentpropositions')
        ->join('answers', 'currentpropositions.answer_id', '=', 'answers.id')
        ->where('currentpropositions.game_id', $this->id)
        ->select('currentpropositions.player_id', 'answers.text', 'currentpropositions.answer_id', 'currentpropositions.order')
        ->get()
        ->groupBy('player_id');

        // Put the propositions in a random order
        $propositions = collect($propositions)->shuffle();

        return $propositions;
    }

    /**
     * Get all the players having sent a proposition
     * @return array
     */
    public function getPlayersHavingSentProposition()
    {

        $players = $this->propositions('player_id')->get()->groupBy('player_id')->keys();

        return $players;
    }

    /**
     * Check if the game is over (score_goal reached by one player)
     * @return boolean
     */
    public function isThereAWinner()
    {
        $winner = $this->players()->where('current_score', '>=', $this->score_goal)->first();
        return $winner ?? false;
    }

    /**
     * Discard all cards that were in the currentpropositions table
     */
    public function discardAllPropositions()
    {
        foreach ($this->propositions as $proposition) {
            // remove answer's owner
            $proposition->answer->player()->dissociate();
            $proposition->answer->status = 'discarded';
            $proposition->answer->save();
            // then, remove all game's propositions
            Proposition::where('game_id', $this->id)->delete();
        }
    }
}
