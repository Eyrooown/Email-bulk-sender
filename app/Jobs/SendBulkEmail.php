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
use Illuminate\Support\Facades\Config;
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
        public string $livewireId,
        public array $csvData = [], // keyed by email => row data
        public ?string $smtpUsername = null,
        public ?string $smtpPassword = null,
        public ?string $fromAddress = null,
        public ?string $fromName = null,
        public ?string $replyToEmail = null,
    ) {}

    public function handle(): void
    {
        $total = count($this->recipients);

        $templateBody = $this->body;
        $effectiveSmtpUsername = $this->smtpUsername ?: config('mail.mailers.smtp.username');
        if (!empty($effectiveSmtpUsername)) {
            Config::set('mail.mailers.smtp.username', $effectiveSmtpUsername);
        }
        Mail::purge('smtp');

        foreach ($this->recipients as $index => $recipient) {
            try {
                // Per-recipient merge-tag resolution
                $resolvedBody = $templateBody;
                $row = $this->findRowDataForRecipient($recipient);
                $normalizedRow = [];
                foreach ($row as $column => $value) {
                    $normalizedRow[$this->normalizeMergeKey((string) $column)] = (string) $value;
                }

                $resolvedBody = preg_replace_callback('/\{\{\s*(.*?)\s*\}\}/u', function ($matches) use ($normalizedRow) {
                    $rawKey = (string) ($matches[1] ?? '');
                    $normalizedKey = $this->normalizeMergeKey($rawKey);

                    return $normalizedRow[$normalizedKey] ?? '';
                }, $resolvedBody);

                $mailable = new BulkEmail(
                    $this->subject,
                    $resolvedBody,
                    $this->fromAddress,
                    $this->fromName,
                    $this->replyToEmail
                );

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

    private function findRowDataForRecipient(string $recipient): array
    {
        if (isset($this->csvData[$recipient]) && is_array($this->csvData[$recipient])) {
            return $this->csvData[$recipient];
        }

        $recipientLower = strtolower(trim($recipient));
        foreach ($this->csvData as $email => $row) {
            if (strtolower(trim((string) $email)) === $recipientLower && is_array($row)) {
                return $row;
            }
        }

        return [];
    }

    private function normalizeMergeKey(string $key): string
    {
        $key = preg_replace('/\s+/u', ' ', trim($key)) ?? trim($key);

        return strtolower($key);
    }
}
