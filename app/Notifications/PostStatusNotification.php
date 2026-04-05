<?php

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
// use Illuminate\Contracts\Queue\ShouldQueue;
// use Illuminate\Notifications\Messages\MailMessage;

class PostStatusNotification extends Notification
{
    use Queueable;

    protected $post;
    protected $statusText;
 
    public function __construct(Post $post, $statusText)
    {
        $this->post = $post;
        $this->statusText = $statusText;
    }
 
    public function via($notifiable)
    { 
        return ['database'];

    }
 
    public function toArray($notifiable)
    {
        return [
            'message' => "თქვენი პოსტი '{$this->post->title}' {$this->statusText}.",
            'post_id' => $this->post->id,
        ];
    }

    // public function toMail($notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //         ->subject('სიახლე თქვენი პოსტის შესახებ')  
    //         ->greeting("გამარჯობა, {$notifiable->name}!")   
    //         ->line("თქვენი პოსტი '{$this->post->title}' {$this->statusText}.")  
    //         ->action('პოსტის ნახვა', url('/posts/' . $this->post->id))
    //         ->line('გმადლობთ, რომ სარგებლობთ ჩვენი პლატფორმით!');  
    // }
}
