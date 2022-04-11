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
use Illuminate\Support\Facades\Log;


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
    public function __construct(int $game_id, string $cardpack)
    {
        $this->game = Game::find($game_id);
        $this->cardpack = $cardpack;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->game->cardsGeneration($this->cardpack);
        // TODO : Broadcast to owner that cards are ready
    }
}
