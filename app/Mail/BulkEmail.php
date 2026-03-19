<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BulkEmail extends Mailable
{
    use Queueable, SerializesModels;

    public string $emailBody;
    public string $emailSubject;
    public ?string $fromName;
    public ?string $replyToEmail;

    public function __construct(string $subject, string $body, ?string $fromName = null, ?string $replyToEmail = null)
    {
        $this->emailSubject = $subject;
        $this->emailBody = $body;
        $this->fromName = $fromName;
        $this->replyToEmail = $replyToEmail;
    }

    public function build()
    {
        $fromAddress = config('mail.from.address');
        $effectiveFromName = $this->fromName ?: config('mail.from.name');

        $mail = $this->from($fromAddress, $effectiveFromName)
            ->subject($this->emailSubject)
            ->view('emails.bulk');

        if (!empty($this->replyToEmail)) {
            $mail->replyTo($this->replyToEmail, $effectiveFromName);
        }

        return $mail;
    }
}
