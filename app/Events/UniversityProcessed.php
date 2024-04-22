<?php

namespace App\Events;

use App\Models\University;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UniversityProcessed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var University */
    public $university;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(University $university)
    {
        $this->university = $university;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
