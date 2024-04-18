<?php

namespace App\Events;

use App\Models\Catalog;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CatalogActivation
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var Catalog */
    public $catalog;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Catalog $catalog)
    {
        $this->catalog = $catalog;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|PrivateChannel
     */
    public function broadcastOn(): Channel|PrivateChannel
    {
        return new PrivateChannel('channel-name');
    }
}
