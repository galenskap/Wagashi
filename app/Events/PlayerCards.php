<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PlayerCards implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $player;
    public $answers;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($player)
    {
        $this->player = $player;
        $this->answers = $player->answers;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('player-'.$this->player->id);
    }
}
