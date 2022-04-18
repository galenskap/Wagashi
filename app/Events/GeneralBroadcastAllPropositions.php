<?php

namespace App\Events;

use App\Models\Game;
use Illuminate\Support\Facades\Log;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class GeneralBroadcastAllPropositions implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $game;
    public $propositions;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(int $game_id)
    {
        $this->game = Game::find($game_id);
        $this->propositions = $this->game->getAllPropositions();
        Log::debug("------------GBAllPropositions construct----------");
        Log::debug($this->game);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        Log::debug("------------GBAllPropositions broadcaston----------");
        Log::debug($this->game);
        $channel = 'general-' . $this->game->id;
        return new PrivateChannel($channel);
    }

}
