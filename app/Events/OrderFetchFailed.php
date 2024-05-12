<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class OrderFetchFailed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $responseStatus;
    public $responseBody;

    /**
     * Create a new event instance.
     *
     * @param int $responseStatus
     * @param string $responseBody
     */
    public function __construct(int $responseStatus, string $responseBody)
    {
        $this->responseStatus = $responseStatus;
        $this->responseBody = $responseBody;
        
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
