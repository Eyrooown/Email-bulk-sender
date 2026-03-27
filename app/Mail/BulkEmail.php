<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BulkEmail extends Mailable
{
    use Queueable, SerializesModels;

    public string $emailBody;
    public string $emailSubject;
    public ?string $fromAddress;
    public ?string $fromName;
    public ?string $replyToEmail;

    public function __construct(
        string $subject,
        string $body,
        ?string $fromAddress = null,
        ?string $fromName = null,
        ?string $replyToEmail = null
    )
    {
        $this->emailSubject = $subject;
        $this->emailBody = $body;
        $this->fromAddress = $fromAddress;
        $this->fromName = $fromName;
        $this->replyToEmail = $replyToEmail;
    }

    public function envelope(): Envelope
    {
        $fromAddress = $this->fromAddress ?: config('mail.from.address');
        $effectiveFromName = $this->fromName ?: config('mail.from.name');

        $replyTo = [];
        if (!empty($this->replyToEmail)) {
            $replyTo[] = new Address($this->replyToEmail, $effectiveFromName);
        }

        return new Envelope(
            from: new Address($fromAddress, $effectiveFromName),
            replyTo: $replyTo,
            subject: $this->emailSubject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.bulk',
            with: [
                'emailBody' => $this->emailBody,
            ],
        );
    }
}
