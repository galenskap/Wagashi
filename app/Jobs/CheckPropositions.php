<?php

namespace App\Jobs;

use App\Models\Game;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Events\GeneralBroadcastNewProposition;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use App\Events\GeneralBroadcastAllPropositions;

class CheckPropositions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $game;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $game_id)
    {
        $this->game = Game::find($game_id);
        Log::debug("------------CheckPropositions construct----------");
        Log::debug($this->game);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Broadcast the information that the player has submitted a proposition
        GeneralBroadcastNewProposition::dispatch($this->game->id);
        Log::debug("------------CheckPropositions handle----------");
        Log::debug($this->game);

        if ($this->game->areAllPropositionsSent()) {
            // Broadcast to general all the propositions
            GeneralBroadcastAllPropositions::dispatch($this->game->id);
            Log::debug("------------CheckPropositions handle condition----------");
            Log::debug($this->game);
        }
    }
}
