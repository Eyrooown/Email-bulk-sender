<?php

namespace App\Jobs;

use App\Mail\BulkEmail;
use App\Models\Email;
use App\Models\EmailAttachment;
use App\Models\EmailRecipient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class SendBulkEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $emailId,
        public array $recipients,
        public string $subject,
        public string $body,
        public array $attachmentPaths,
        public string $livewireId
    ) {}

    public function handle(): void
    {
        $total = count($this->recipients);

        foreach ($this->recipients as $index => $recipient) {
            try {
                $mailable = new BulkEmail($this->subject, $this->body);

                foreach ($this->attachmentPaths as $path) {
                    $mailable->attach(storage_path('app/public/' . $path['path']), [
                        'as'   => $path['filename'],
                    ]);
                }

                Mail::to($recipient)->send($mailable);

                EmailRecipient::create([
                    'email_id' => $this->emailId,
                    'email'    => $recipient,
                    'status'   => 'sent',
                ]);

            } catch (\Exception $e) {
                \Log::error('Failed: ' . $recipient . ' - ' . $e->getMessage());

                EmailRecipient::create([
                    'email_id' => $this->emailId,
                    'email'    => $recipient,
                    'status'   => 'failed',
                ]);
            }

            // Dispatch progress event
            event(new \App\Events\EmailProgress(
                $this->livewireId,
                $index + 1,
                $total,
                $recipient
            ));
        }

        // Dispatch done event
        event(new \App\Events\EmailProgress(
            $this->livewireId,
            $total,
            $total,
            'done'
        ));
    }
}
