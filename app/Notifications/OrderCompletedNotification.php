<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCompletedNotification extends Notification
{
    public function __construct(
        private readonly string $orderId,
        private readonly string $foods,
        private readonly float  $total,
        private readonly string $branch
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your HostelEats Order is Completed! 🎉')
            ->greeting('Hello, ' . $notifiable->name . '!')
            ->line('Great news! Your order has been **completed** and is ready for pickup.')
            ->line('---')
            ->line('**Order ID:** ' . $this->orderId)
            ->line('**Branch:** ' . $this->branch)
            ->line('**Items:** ' . $this->foods)
            ->line('**Total:** PHP ' . number_format($this->total, 2))
            ->line('---')
            ->line('Thank you for ordering from HostelEats! 🍔')
            ->salutation('— The HostelEats Team');
    }
}
