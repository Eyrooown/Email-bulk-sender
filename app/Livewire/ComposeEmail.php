<?php

namespace App\Livewire;

use App\Mail\BulkEmail;
use App\Models\Email;
use App\Models\EmailAttachment;
use App\Models\EmailRecipient;
use App\Livewire\SendingProgressToast;
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
    public string $csvParseError = '';
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
        $this->csvParseError = '';
        $this->resetValidation('csvFile');
    }

    public function closeCsvModal()
    {
        $this->showCsvModal = false;
        $this->csvFile = null;
        $this->csvParseError = '';
        $this->resetValidation('csvFile');
    }

    public function updatedCsvFile()
    {
        $this->csvParseError = '';
        $this->csvHeaders = [];
        $this->csvRows = [];
        $this->selectedEmailColumn = '';

        if (!$this->csvFile) {
            return;
        }

        $this->validateOnly('csvFile');

        $path = $this->csvFile->getRealPath();
        if (!$path || !is_file($path)) {
            $this->csvParseError = 'Upload not ready yet. Please wait a moment and try again.';
            return;
        }

        try {
            // Detect delimiter from first non-empty line
            $firstLine = '';
            $fh = fopen($path, 'rb');
            if ($fh) {
                while (($line = fgets($fh)) !== false) {
                    if (trim($line) !== '') {
                        $firstLine = $line;
                        break;
                    }
                }
                fclose($fh);
            }

            if ($firstLine === '') {
                $this->csvParseError = 'CSV file appears to be empty.';
                return;
            }

            $candidates = [',', ';', "\t", '|'];
            $bestDelimiter = ',';
            $bestCount = 0;
            foreach ($candidates as $delim) {
                $count = count(str_getcsv($firstLine, $delim));
                if ($count > $bestCount) {
                    $bestCount = $count;
                    $bestDelimiter = $delim;
                }
            }

            $file = new \SplFileObject($path, 'rb');
            $file->setFlags(\SplFileObject::READ_CSV | \SplFileObject::SKIP_EMPTY | \SplFileObject::DROP_NEW_LINE);
            $file->setCsvControl($bestDelimiter);

            $headers = null;
            foreach ($file as $row) {
                if (!is_array($row)) {
                    continue;
                }
                // Some CSV parsers return [null] at EOF
                if (count($row) === 1 && $row[0] === null) {
                    continue;
                }

                // Trim all cells
                $row = array_map(static fn($v) => is_string($v) ? trim($v) : $v, $row);

                if ($headers === null) {
                    // Remove UTF-8 BOM if present
                    if (isset($row[0]) && is_string($row[0])) {
                        $row[0] = preg_replace('/^\xEF\xBB\xBF/', '', $row[0]) ?? $row[0];
                    }
                    $headers = $row;
                    continue;
                }

                // Ignore completely empty rows
                $hasAny = false;
                foreach ($row as $cell) {
                    if (is_string($cell) && $cell !== '') {
                        $hasAny = true;
                        break;
                    }
                }
                if (!$hasAny) {
                    continue;
                }

                $this->csvRows[] = $row;
            }

            $this->csvHeaders = $headers ?? [];

            if (count($this->csvHeaders) < 1 || count($this->csvRows) < 1) {
                $this->csvParseError = 'Could not read rows from this CSV. Make sure it has a header row and at least 1 data row.';
                return;
            }

            // Auto detect email column
            foreach ($this->csvHeaders as $index => $header) {
                if (is_string($header) && stripos($header, 'email') !== false) {
                    $this->selectedEmailColumn = (string) $index;
                    break;
                }
            }
        } catch (\Throwable $e) {
            $this->csvParseError = 'Failed to parse CSV: ' . $e->getMessage();
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
            $this->csvData,
            Auth::user()?->name,
            Auth::user()?->email
        );

        $this->dispatch('startProgressToast', emailId: $email->id, total: count($this->recipients))->to(SendingProgressToast::class);

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
