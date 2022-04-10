<?php

namespace App\Jobs;

use App\Models\Game;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class CardsCreation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $game;
    public $cardpack;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Game $game, string $cardpack)
    {
        $this->game = $game;
        $this->cardpack = $cardpack;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // get corresponding files from storage dir
        //$questions_txt = Storage::get('cardpacks/' . $this->cardpack . '/questions.txt');
        //$answers_txt = Storage::get('cardpacks/' . $this->cardpack . '/answers.txt');
        Log::debug('$questions_txt');

        // read txt file
        //$questions = explode("\n", $questions_txt);
        //$answers = explode("\n", $answers_txt);

        // create questions
        return true;
    }
}
