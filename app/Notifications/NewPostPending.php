<?php

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Notifications\Notification;

class NewPostPending extends Notification
{
    protected $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "ახალი პოსტი '{$this->post->title}' ელოდება მოდერაციას.",
            'post_id' => $this->post->id,
            'author' => $this->post->user->name
        ];
    }
}
