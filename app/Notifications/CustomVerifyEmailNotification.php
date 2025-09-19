<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class CustomVerifyEmailNotification extends VerifyEmail
{
    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        // Parse the Laravel verification URL to extract parameters
        $parsedUrl = parse_url($verificationUrl);
        parse_str($parsedUrl['query'] ?? '', $queryParams);

        // Extract ID and hash from the path
        $pathParts = explode('/', trim($parsedUrl['path'], '/'));
        $id = $pathParts[3] ?? $notifiable->getKey(); // /api/email/verify/{id}/{hash}
        $hash = $pathParts[4] ?? '';

        // Build frontend URL with query parameters
        $frontendUrl = env('FRONTEND_URL', 'http://localhost:5040') . '/email-verify?' . http_build_query([
            'id' => $id,
            'hash' => $hash,
            'expires' => $queryParams['expires'] ?? '',
            'signature' => $queryParams['signature'] ?? ''
        ]);

        // Use custom email template
        return (new MailMessage)
            ->subject('Подтверждение Email - BONUS5')
            ->view('emails.verification-russian', [
                'verificationUrl' => $frontendUrl,
                'user' => $notifiable
            ]);
    }
}
