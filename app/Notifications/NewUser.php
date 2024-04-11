<?php

namespace App\Notifications;

use App\Models\Retailer;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUser extends Notification
{
    use Queueable;

    private Retailer $new_user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Retailer $new_user)
    {
        $this->new_user = $new_user;
    }


    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('New user has registered with email ' . $this->new_user->email)
            ->action('Approve user', route('admin.users.approve', $this->new_user->id));
    }
//    public function via ($notifiable): array
//    {
//        return ['mail'];
//    }

}
