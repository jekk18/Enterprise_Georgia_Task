<?php

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PostStatusNotification extends Notification
{
    use Queueable;

    protected $post;
    protected $statusText;

    // კონსტრუქტორით ვიღებთ პოსტს და სტატუსს (დადასტურდა/უარყოფილია)
    public function __construct(Post $post, $statusText)
    {
        $this->post = $post;
        $this->statusText = $statusText;
    }

    // ვუთითებთ, რომ ნოთიფიკაცია ჩაიწეროს მონაცემთა ბაზაში
    public function via($notifiable)
    {
        return ['database'];
    }

    // რა ინფორმაცია შეინახოს ბაზაში 'data' სვეტში
    public function toArray($notifiable)
    {
        return [
            'message' => "თქვენი პოსტი '{$this->post->title}' {$this->statusText}.",
            'post_id' => $this->post->id,
        ];
    }
}
