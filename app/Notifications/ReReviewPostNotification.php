<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReReviewPostNotification extends Notification
{
    use Queueable;

    // Properties-ის განსაზღვრა აუცილებელია, რომ კლასმა შიგნით დაინახოს ეს მონაცემები
    protected $post;
    protected $adminName;

    /**
     * Create a new notification instance.
     */
    public function __construct($post, $adminName)
    {
        $this->post = $post;
        $this->adminName = $adminName;
    }

    /**
     * განსაზღვრავს, სად უნდა შეინახოს ნოთიფიკაცია. 
     * 'database' ნიშნავს, რომ ჩაიწერება notifications ცხრილში.
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * მონაცემები, რომლებიც შეინახება ბაზაში 'data' სვეტში.
     */
    public function toArray($notifiable)
    {
        return [
            'post_id' => $this->post->id,
            'message' => "ადმინისტრატორმა {$this->adminName}-მა გადმოგიგზავნათ პოსტი ხელახალი განხილვისთვის.",
            'title' => $this->post->title,
        ];
    }
}
