<?php

namespace App\Events;

use App\Models\Game;
use App\Models\Player;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class GeneralBroadcastRoundWinner implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $game;
    public $answers;
    public $question;
    public $winner;
    public $players;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(int $game_id, int $player_id, $question, $answers)
    {
        $this->game = Game::find($game_id);
        $player = Player::find($player_id);

        $this->answers = $answers;
        $this->question = $question;
        $this->winner = $player->id;
        $this->players = $this->game->players;
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
