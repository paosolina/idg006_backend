<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;



class EmailVerificationNotification extends Notification
{
    use Queueable;


    private ?string $callback_url;
    /**
     * Create a new notification instance.
     */
    public function __construct($callback_url = null)
    {
        $this->callback_url = $callback_url;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $verifycationURL = $this->verficationURL($notifiable);
        return (new MailMessage)
            ->subject('Email Verification')
            ->line('Verify your email address')
            ->action('ផ្ទៀងផ្ទាត់ Email', $this->callback_url . '?forward_url=' . urlencode($verifycationURL))
            ->line('Thank you for using our application!')
            ->line('This verification url will expire in 5 minutes');
    }
    

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
    protected function verficationURL($notifiable)
    {
        return URL::temporarySignedRoute(
            'verify.mail',
            Carbon::now()->addMinutes(5), [
            'id' => $notifiable->getKey(),
            'hash' => sha1($notifiable->getEmailForVerification())
        ]);
    }
}
