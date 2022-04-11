<?php

namespace App\Jobs;

use App\Models\Game;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class CheckPropositions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $game_id)
    {
        $this->game = Game::find($game_id);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $nbPlayersPlayed = $this->game->checkNumberOfPropositions();
        // TODO : Broadcast to general the number of players who played

        if ($this->game->areAllPropositionsSent()) {
            // TODO: broadcast to general the propositions
        }
    }
}
