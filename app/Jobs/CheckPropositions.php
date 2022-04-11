<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
        $nbPlayersPlayed = $this->game->checkPropositions();
        // TODO : Broadcast to general the number of players who played

        if ($this->game->checkPropositions()) {
            // TODO: broadcast to general the propositions
        }
    }
}
