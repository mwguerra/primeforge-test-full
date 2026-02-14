<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

class DemoProgress implements ShouldBroadcastNow
{
    use Dispatchable;

    public string $message;
    public int $progress;
    public string $timestamp;

    public function __construct(string $message, int $progress)
    {
        $this->message = $message;
        $this->progress = $progress;
        $this->timestamp = now()->toDateTimeString();
    }

    public function broadcastOn(): array
    {
        return [new Channel('demo-channel')];
    }
}
