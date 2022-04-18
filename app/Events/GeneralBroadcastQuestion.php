<?php

namespace App\Events;

use App\Models\Game;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class GeneralBroadcastQuestion implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $game;
    public $question;
    public $dealer_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(int $game_id, string $question, int $dealer_id)
    {
        $this->game = Game::find($game_id);
        $this->question = $question;
        $this->dealer_id = $dealer_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $channel = 'general-' . $this->game->id;
        return new PrivateChannel($channel);
    }

}
