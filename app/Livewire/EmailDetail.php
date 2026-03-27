<?php

namespace App\Livewire;

use App\Models\Email;
use App\Models\EmailAttachment;
use App\Models\EmailRecipient;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class EmailDetail extends Component
{
    use WithPagination, WithFileUploads;

    public bool $editMode = false;
    public array $recipients = [];
    public bool $showSendingModal = false;
    public ?int $sendingEmailId = null;
    public int $sendTotal = 0;
    public int $sendCurrent = 0;
    public string $sendCurrentEmail = '';
    public bool $showToast = false;
    public string $toastMessage = '';
    public string $manualEmail = '';
    public string $manualEmailError = '';
    public bool $showAllRecipients = false;
    public int $emailId;
    public string $subject = '';
    public string $body = '';
    public array $removedAttachmentIds = [];
    public $attachments = [];

    public function mount(int $email)
    {
        $this->emailId = $email;
        $model = Email::with('recipients', 'attachments')->findOrFail($email);
        $this->recipients = $model->recipients->pluck('email')->toArray();
    }

    public function toggleEdit()
    {
        $this->editMode = !$this->editMode;
        $this->resetPage('recipientsPage');
        $model = Email::with('recipients')->findOrFail($this->emailId);
        $this->recipients = $model->recipients->pluck('email')->toArray();
        $this->subject = $model->subject ?: '';
        $this->body = $model->body ?: '';
        $this->manualEmail = '';
        $this->manualEmailError = '';
        $this->removedAttachmentIds = [];
        $this->attachments = [];
    }

    public function removeExistingAttachment(int $attachmentId)
    {
        $this->removedAttachmentIds[] = $attachmentId;
    }

    public function removeNewAttachment(int $index)
    {
        array_splice($this->attachments, $index, 1);
    }

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
        $this->manualEmail = '';
    }

    public function removeRecipient(string $recipientEmail)
    {
        $this->recipients = array_values(array_filter($this->recipients, fn($r) => $r !== $recipientEmail));
    }

    public function saveChanges()
    {
        $model = Email::with('recipients')->findOrFail($this->emailId);
        $existing = $model->recipients->pluck('email')->toArray();

        foreach ($this->recipients as $recipientEmail) {
            if (!in_array($recipientEmail, $existing)) {
                EmailRecipient::create([
                    'email_id' => $this->emailId,
                    'email'    => $recipientEmail,
                    'status'   => 'pending',
                ]);
            }
        }

        foreach ($existing as $recipientEmail) {
            if (!in_array($recipientEmail, $this->recipients)) {
                EmailRecipient::where('email_id', $this->emailId)
                    ->where('email', $recipientEmail)
                    ->delete();
            }
        }

        $this->editMode = false;
    }

    public function sendAgain($body = '')
    {
        if (empty($this->recipients)) {
            session()->flash('error', 'Please add at least one recipient.');
            return;
        }

        $this->body = is_array($body) ? ($body['body'] ?? '') : (string) $body;
        if (empty(strip_tags($this->body))) {
            session()->flash('error', 'Please write something before sending.');
            return;
        }

        $this->saveChanges();

        $email = Email::with('attachments')->findOrFail($this->emailId);

        $newEmail = Email::create([
            'user_id' => Auth::id(),
            'subject' => $this->subject ?: '(No Subject)',
            'body'    => $this->body,
            'status'  => 'sent',
        ]);

        $attachmentPaths = [];

        foreach ($email->attachments as $attachment) {
            if (in_array($attachment->id, $this->removedAttachmentIds)) {
                continue;
            }
            EmailAttachment::create([
                'email_id' => $newEmail->id,
                'filename' => $attachment->filename,
                'path'     => $attachment->path,
            ]);
            $attachmentPaths[] = ['path' => $attachment->path, 'filename' => $attachment->filename];
        }

        foreach ($this->attachments ?? [] as $file) {
            $path = $file->store('email_attachments', 'public');
            EmailAttachment::create([
                'email_id' => $newEmail->id,
                'filename' => $file->getClientOriginalName(),
                'path'     => $path,
            ]);
            $attachmentPaths[] = ['path' => $path, 'filename' => $file->getClientOriginalName()];
        }

        Cache::put("sending_progress_{$newEmail->id}", [
            'current' => 0,
            'total'   => count($this->recipients),
            'currentEmail' => 'Starting...',
        ], 300);

        \App\Jobs\SendBulkEmail::dispatch(
            $newEmail->id,
            $this->recipients,
            $this->subject ?: '(No Subject)',
            $this->body,
            $attachmentPaths,
            $this->getId(),
            [], // no csv data for resend from details
            null,
            null,
            null,
            \Illuminate\Support\Facades\Auth::user()?->name,
            \Illuminate\Support\Facades\Auth::user()?->email
        );

        $this->sendingEmailId = $newEmail->id;
        $this->showSendingModal = true;
        $this->sendTotal = count($this->recipients);
        $this->sendCurrent = 0;
    }

    public function checkSendingProgress()
    {
        if (!$this->sendingEmailId) return;

        $progress = Cache::get("sending_progress_{$this->sendingEmailId}");
        if ($progress) {
            $this->sendCurrent = $progress['current'];
            $this->sendTotal = $progress['total'];
            $this->sendCurrentEmail = $progress['currentEmail'];

            if ($progress['currentEmail'] === 'done') {
                $this->showSendingModal = false;
                $this->sendingEmailId = null;
                $this->showToast = true;
                $this->toastMessage = 'Email sent to ' . $progress['total'] . ' recipient' . ($progress['total'] !== 1 ? 's' : '');
                Cache::forget("sending_progress_{$this->sendingEmailId}");
                $this->redirect(route('dashboard'), navigate: true);
            }
        }
    }

    public function render()
    {
        $email = Email::with('recipients', 'attachments')->findOrFail($this->emailId);

        if ($this->editMode) {
            $page = max(1, (int) request()->query('recipientsPage', 1));
            $recipientsPaginator = new LengthAwarePaginator(
                array_slice($this->recipients, ($page - 1) * 25, 25),
                count($this->recipients),
                25,
                $page,
                ['path' => LengthAwarePaginator::resolveCurrentPath(), 'pageName' => 'recipientsPage']
            );
        } else {
            $recipientsPaginator = EmailRecipient::where('email_id', $this->emailId)
                ->orderBy('id')
                ->paginate(25, ['*'], 'recipientsPage');
        }

        $visibleRecipients = $this->showAllRecipients
            ? $this->recipients
            : array_slice($this->recipients, 0, 4);

        return view('components.livewire.email-detail', [
            'email'               => $email,
            'visibleRecipients'   => $visibleRecipients,
            'recipientsPaginator' => $recipientsPaginator,
        ]);
    }
}
