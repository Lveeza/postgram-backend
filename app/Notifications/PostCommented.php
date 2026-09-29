<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Notifications\Messages\BroadcastMessage;

class PostCommented extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public User $commenter, public Comment $comment) {}

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'commenter_id' => $this->commenter->id,
            'name'         => $this->commenter->name,
            'avatar'       => $this->commenter->avatar_url ?? null,
            'post_id'      => $this->comment->post_id,
            'comment_id'   => $this->comment->id,
            'message'      => "{$this->commenter->name} commented on your post.",
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'commenter_id' => $this->commenter->id,
            'name'         => $this->commenter->name,
            'avatar'       => $this->commenter->avatar_url ?? null,
            'post_id'      => $this->comment->post_id,
            'comment_id'   => $this->comment->id,
            'message'      => "{$this->commenter->name} commented on your post.",
        ]);
    }
}
