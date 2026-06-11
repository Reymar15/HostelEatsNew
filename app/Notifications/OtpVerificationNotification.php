<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OtpVerificationNotification extends Notification
{
    public function __construct(private readonly string $otp) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your HostelEats Verification Code')
            ->greeting('Hello, ' . $notifiable->name . '!')
            ->line('Use the code below to verify your HostelEats account.')
            ->line('Your 6-digit verification code is:')
            ->line('## ' . $this->otp)
            ->line('This code expires in **10 minutes**.')
            ->line('If you did not create a HostelEats account, you can ignore this email.')
            ->salutation('— The HostelEats Team');
    }
}
