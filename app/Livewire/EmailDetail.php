<?php

namespace App\Livewire;

use App\Models\Email;
use App\Models\EmailRecipient;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithPagination;

class EmailDetail extends Component
{
    use WithPagination;
    public bool $editMode = false;
    public array $recipients = [];
    public string $manualEmail = '';
    public string $manualEmailError = '';
    public bool $showAllRecipients = false;
    public int $emailId;

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
        $this->manualEmail = '';
        $this->manualEmailError = '';
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
