<?php

namespace App\Livewire;

use App\Mail\BulkEmail;
use App\Models\Email;
use App\Models\EmailAttachment;
use App\Models\EmailRecipient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithFileUploads;

class ComposeEmail extends Component
{
    use WithFileUploads;

    public string $subject = '';
    public string $body = '';
    public string $manualEmail = '';
    public array $recipients = [];
    public array $recipientStatuses = [];
    public $attachments = [];
    public $csvFile = null;
    public array $csvHeaders = [];
    public array $csvRows = [];
    public string $selectedEmailColumn = '';
    public bool $showCsvModal = false;
    public bool $showOverlay = false;
    public bool $showToast = false;
    public string $toastMessage = '';
    public bool $showAllRecipients = false;
    public string $manualEmailError = '';
    public int $sendTotal = 0;
    public int $sendCurrent = 0;
    public string $sendCurrentEmail = '';

    protected $rules = [
        'subject'      => 'nullable|string|max:255',
        'body'         => 'required|string',
        'manualEmail'  => 'nullable|email',
        'attachments.*'=> 'file|max:10240',
        'csvFile'      => 'nullable|file|mimes:csv,txt',
    ];

    public function addRecipient()
    {
        $this->manualEmailError = '';

        if (empty($this->manualEmail)) {
            $this->manualEmailError = 'Please enter a valid email address.';
            return;
        }

        if (!filter_var($this->manualEmail, FILTER_VALIDATE_EMAIL)) {
            $this->manualEmailError = 'Please enter a valid email address.';
            return;
        }

        if (in_array($this->manualEmail, $this->recipients)) {
            $this->manualEmailError = 'This email is already added.';
            return;
        }

        $this->recipients[] = $this->manualEmail;
        $this->recipientStatuses[$this->manualEmail] = 'pending';
        $this->manualEmail = '';
    }

    public function removeRecipient(string $email)
    {
        $this->recipients = array_values(array_filter($this->recipients, fn($r) => $r !== $email));
        unset($this->recipientStatuses[$email]);
    }

    public function removeAttachment(int $index)
    {
        array_splice($this->attachments, $index, 1);
    }

    public function openCsvModal()
    {
        $this->showCsvModal = true;
        $this->csvFile = null;
        $this->csvHeaders = [];
        $this->csvRows = [];
        $this->selectedEmailColumn = '';
    }

    public function closeCsvModal()
    {
        $this->showCsvModal = false;
        $this->csvFile = null;
        $this->csvHeaders = [];
        $this->csvRows = [];
        $this->selectedEmailColumn = '';
    }

    public function updatedCsvFile()
    {
        if (!$this->csvFile) return;

        $content = file_get_contents($this->csvFile->getRealPath());
        $lines = array_filter(explode("\n", str_replace("\r\n", "\n", $content)));
        $lines = array_values($lines);

        if (count($lines) < 2) return;

        $this->csvHeaders = str_getcsv($lines[0]);
        $this->csvRows = [];

        foreach (array_slice($lines, 1) as $line) {
            if (trim($line)) {
                $this->csvRows[] = str_getcsv($line);
            }
        }

        // Auto detect email column
        foreach ($this->csvHeaders as $index => $header) {
            if (stripos($header, 'email') !== false) {
                $this->selectedEmailColumn = (string) $index;
                break;
            }
        }
    }

    public function importFromCsv()
    {
        if ($this->selectedEmailColumn === '') return;

        $colIndex = (int) $this->selectedEmailColumn;

        foreach ($this->csvRows as $row) {
            $email = trim($row[$colIndex] ?? '');
            if (filter_var($email, FILTER_VALIDATE_EMAIL) && !in_array($email, $this->recipients)) {
                $this->recipients[] = $email;
                $this->recipientStatuses[$email] = 'pending';
            }
        }

        $this->closeCsvModal();
    }

    public function sendWithBody(string $body)
    {
        $this->body = $body;
        $this->send();
    }

    public function send()
    {
        if (empty($this->recipients)) {
            $this->addError('recipients', 'Please add at least one recipient.');
            return;
        }

        if (empty(strip_tags($this->body))) {
            $this->addError('body', 'Please write something before sending.');
            return;
        }

        $this->sendTotal = count($this->recipients);
        $this->sendCurrent = 0;
        $this->showOverlay = true;

        // Create email record
        $email = Email::create([
            'user_id' => Auth::id(),
            'subject' => $this->subject ?: '(No Subject)',
            'body'    => $this->body,
            'status'  => 'sent',
        ]);

        // Store attachments first
        $attachmentPaths = [];
        if (!empty($this->attachments)) {
            foreach ($this->attachments as $attachment) {
                $path = $attachment->store('email_attachments', 'public');
                EmailAttachment::create([
                    'email_id' => $email->id,
                    'filename' => $attachment->getClientOriginalName(),
                    'path'     => $path,
                ]);
                $attachmentPaths[] = [
                    'path'     => $path,
                    'filename' => $attachment->getClientOriginalName(),
                ];
            }
        }

        // Dispatch job
        \App\Jobs\SendBulkEmail::dispatch(
            $email->id,
            $this->recipients,
            $this->subject ?: '(No Subject)',
            $this->body,
            $attachmentPaths,
            $this->getId()
        );

        // Reset form but keep overlay open
        $this->subject = '';
        $this->body = '';
        $this->attachments = [];
        $this->recipients = [];
        $this->recipientStatuses = [];
        $this->sendCurrent = 0;
        // showOverlay stays true until JS listener closes it
    }

    public function dismissToast()
    {
        $this->showToast = false;
        $this->redirect(route('dashboard'));
    }

    public function render()
    {
        $visibleRecipients = $this->showAllRecipients
            ? $this->recipients
            : array_slice($this->recipients, 0, 4);

        $previewRows = array_slice($this->csvRows, 0, 5);

        return view('components.livewire.compose-email', [
            'visibleRecipients' => $visibleRecipients,
            'previewRows'       => $previewRows,
        ]);
    }
}
