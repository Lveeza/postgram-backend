<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\User;
use App\Models\Story;
use Illuminate\Notifications\Messages\BroadcastMessage;

class StoryLiked extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public User $liker, public Story $story) {}

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'liker_id' => $this->liker->id,
            'name'     => $this->liker->name,
            'avatar'   => $this->liker->avatar_url ?? null,
            'story_id' => $this->story->id,
            'message'  => "{$this->liker->name} liked your story.",
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'liker_id' => $this->liker->id,
            'name'     => $this->liker->name,
            'avatar'   => $this->liker->avatar_url ?? null,
            'story_id' => $this->story->id,
            'message'  => "{$this->liker->name} liked your story.",
        ]);
    }
}
