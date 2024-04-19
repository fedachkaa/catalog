<?php

namespace App\Events;

use App\Models\TopicRequest;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TopicRequestProcessed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var TopicRequest */
    public $topicRequest;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(TopicRequest $topicRequest)
    {
        $this->topicRequest = $topicRequest;
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
