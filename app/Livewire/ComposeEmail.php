<?php

namespace App\Livewire;

use App\Mail\BulkEmail;
use App\Models\Email;
use App\Models\EmailAttachment;
use App\Models\EmailRecipient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ComposeEmail extends Component
{
    use WithFileUploads, WithPagination;

    public string $subject = '';
    public string $body = '';
    public string $manualEmail = '';
    public array $recipients = [];
    public array $recipientStatuses = [];
    public $attachments = [];
    public $csvFile = null;
    public array $csvHeaders = [];
    public array $csvRows = [];
    public array $csvData = []; // keyed by email => row data
    public string $selectedEmailColumn = '';
    public bool $showCsvModal = false;
    public bool $showOverlay = false;
    public bool $showSendingModal = false;
    public ?int $sendingEmailId = null;
    public bool $showToast = false;
    public string $toastMessage = '';
    public bool $showAllRecipients = false;
    public string $manualEmailError = '';
    public int $sendTotal = 0;
    public int $sendCurrent = 0;
    public string $sendCurrentEmail = '';
    public ?int $editingDraftId = null;
    public int $recipientsPage = 1;
    public string $recipientsSearch = '';

    public function updatedRecipientsSearch()
    {
        $this->resetPage('recipientsPage');
    }

    public function mount()
    {
        $draftId = request()->query('draft');
        if ($draftId) {
            $draft = Email::where('user_id', Auth::id())
                ->where('status', 'draft')
                ->with('recipients')
                ->find($draftId);
            if ($draft) {
                $this->editingDraftId = $draft->id;
                $this->subject = $draft->subject ?: '';
                $this->body = $draft->body ?: '';
                $this->recipients = $draft->recipients->pluck('email')->all();
                foreach ($this->recipients as $email) {
                    $this->recipientStatuses[$email] = 'pending';
                }
            }
        }
    }

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
        $this->csvData = [];
        $this->selectedEmailColumn = '';
    }

    public function closeCsvModal()
    {
        $this->showCsvModal = false;
        $this->csvFile = null;
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

        $emails = [];
        $csvData = [];

        foreach ($this->csvRows as $row) {
            $email = trim($row[$colIndex] ?? '');
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                continue;
            }

            $emails[] = $email;

            // Map column index → header name → value
            $rowData = [];
            foreach ($this->csvHeaders as $i => $header) {
                $rowData[$header] = $row[$i] ?? '';
            }
            $csvData[$email] = $rowData;
        }

        // Merge into existing recipients and CSV data
        foreach ($emails as $email) {
            if (!in_array($email, $this->recipients)) {
                $this->recipients[] = $email;
                $this->recipientStatuses[$email] = 'pending';
            }
        }

        $this->csvData = $csvData;

        $this->closeCsvModal();
    }

    public function sendWithBody($body = null)
    {
        // When called with payload, update body; otherwise use current Livewire state
        if ($body !== null) {
            $this->body = is_array($body) ? ($body['body'] ?? '') : (string) $body;
        }
        $this->send();
    }

    public function saveDraft($body = '', $redirectTo = '')
    {
        $this->body = is_array($body) ? ($body['body'] ?? '') : (string) $body;

        // Only save if there's something to save (subject, body, or recipients)
        $hasContent = !empty(trim($this->subject))
            || !empty(strip_tags($this->body))
            || !empty($this->recipients);

        if (!$hasContent) {
            if ($redirectTo) {
                $this->redirect($redirectTo, navigate: true);
            }
            return;
        }

        $email = Email::create([
            'user_id' => Auth::id(),
            'subject' => $this->subject ?: '(No Subject)',
            'body'    => $this->body,
            'status'  => 'draft',
        ]);

        foreach ($this->recipients as $recipient) {
            EmailRecipient::create([
                'email_id' => $email->id,
                'email'    => $recipient,
                'status'   => 'pending',
            ]);
        }

        $target = $redirectTo ?: route('draft');
        $this->redirect($target, navigate: true);
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
        $this->showSendingModal = true;

        // Update existing draft or create new email
        if ($this->editingDraftId) {
            $email = Email::where('user_id', Auth::id())
                ->where('status', 'draft')
                ->findOrFail($this->editingDraftId);
            $email->update([
                'subject' => $this->subject ?: '(No Subject)',
                'body'    => $this->body,
                'status'  => 'sent',
            ]);
            $email->recipients()->delete();
        } else {
            $email = Email::create([
                'user_id' => Auth::id(),
                'subject' => $this->subject ?: '(No Subject)',
                'body'    => $this->body,
                'status'  => 'sent',
            ]);
        }

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

        // Initialize progress for polling (job will update it)
        Cache::put("sending_progress_{$email->id}", [
            'current' => 0,
            'total' => count($this->recipients),
            'currentEmail' => 'Starting...',
        ], 300);

        // Dispatch job
        \App\Jobs\SendBulkEmail::dispatch(
            $email->id,
            $this->recipients,
            $this->subject ?: '(No Subject)',
            $this->body,
            $attachmentPaths,
            $this->getId(),
            $this->csvData
        );

        // Reset form, show modal, start polling (keep sendTotal for progress display)
        $this->sendingEmailId = $email->id;
        $this->showSendingModal = true;
        $this->editingDraftId = null;
        $this->subject = '';
        $this->body = '';
        $this->attachments = [];
        $this->recipients = [];
        $this->recipientStatuses = [];
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
                $this->toastMessage = 'Sent to ' . $progress['total'] . ' recipient' . ($progress['total'] !== 1 ? 's' : '');
                Cache::forget("sending_progress_{$this->sendingEmailId}");
                $this->redirect(route('dashboard'), navigate: true);
            }
        }
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

        $filteredRecipients = $this->recipients;
        if (!empty(trim($this->recipientsSearch))) {
            $term = strtolower(trim($this->recipientsSearch));
            $filteredRecipients = array_values(array_filter($this->recipients, fn($email) => str_contains(strtolower($email), $term)));
        }

        $page = max(1, (int) request()->query('recipientsPage', $this->recipientsPage));
        $recipientsPaginator = new LengthAwarePaginator(
            array_slice($filteredRecipients, ($page - 1) * 25, 25),
            count($filteredRecipients),
            25,
            $page,
            ['path' => LengthAwarePaginator::resolveCurrentPath(), 'pageName' => 'recipientsPage']
        );

        return view('components.livewire.compose-email', [
            'visibleRecipients'    => $visibleRecipients,
            'previewRows'          => $previewRows,
            'recipientsPaginator'  => $recipientsPaginator,
            'csvHeaders'           => $this->csvHeaders,
            'csvData'              => $this->csvData,
        ]);
    }
}
