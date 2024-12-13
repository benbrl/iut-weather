<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DailyReportEmail extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $cityId;

    public function __construct($cityId)
    {
        $this->cityId = $cityId;
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
    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //     ->subject('Daily Report for City')
    //     // ->view('emails.daily_report', ['cityId' => $this->cityId]);
    //     ->line('bonjour');
    // }



    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Daily Report for City')
            ->line('Hello, this is your daily report for the city.')
            ->line('You are subscribed to the daily reports for the city with ID: ' . $this->cityId)
            // ->action('View Report', url('/weather?city=' . $this->cityId))
            ->line('Thank you for subscribing to our service!');
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
}
