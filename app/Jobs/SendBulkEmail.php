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
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

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

            // Store progress in cache for Livewire polling
            Cache::put("sending_progress_{$this->emailId}", [
                'current' => $index + 1,
                'total' => $total,
                'currentEmail' => $recipient,
            ], 300);
        }

        // Mark as done
        Cache::put("sending_progress_{$this->emailId}", [
            'current' => $total,
            'total' => $total,
            'currentEmail' => 'done',
        ], 300);
    }
}
