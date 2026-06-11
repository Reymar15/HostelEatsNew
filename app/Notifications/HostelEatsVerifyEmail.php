<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class HostelEatsVerifyEmail extends BaseVerifyEmail
{
    protected function verificationUrl($notifiable): string
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id'   => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }

    public function toMail($notifiable): MailMessage
    {
        $url = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verify Your HostelEats Email Address')
            ->greeting('Hello, ' . $notifiable->name . '!')
            ->line('Welcome to **HostelEats** — your campus food ordering system.')
            ->line('Please click the button below to verify your email address and activate your account.')
            ->action('Verify Email Address', $url)
            ->line('This verification link will expire in **60 minutes**.')
            ->line('If you did not create a HostelEats account, no further action is required.')
            ->salutation('— The HostelEats Team');
    }
}
