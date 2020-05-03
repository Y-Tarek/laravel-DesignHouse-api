<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Auth\Notifications\ResetPassword as Notifications;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends Notifications
{
    use Queueable;


    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = url(config('app.client_url').'/password/reset/'.$this->token).'?email='.urlencode($notifiable->email);
        return (new MailMessage)
                    ->line('You are receiving this mail because you wish to reset your password.')
                    ->action('Reset Password', url($url))
                    ->line('If you didnot ask for password reset so no further action is required!');
    }

    
}
