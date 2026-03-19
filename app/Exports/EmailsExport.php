<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmailsExport implements FromCollection, WithHeadings
{
    public function __construct(
        protected Collection $emails,
        protected bool $includeSender = false,
    ) {}

    public function collection(): Collection
    {
        return $this->emails->map(function ($email) {
            $row = [];

            if ($this->includeSender) {
                $row[] = $email->user->name ?? 'Unknown';
            }

            $row[] = $email->subject;
            $row[] = $email->recipients_count;
            $row[] = ucfirst($email->status);
            $row[] = $email->created_at->timezone('Asia/Manila')->format('M d, Y h:i A');

            return $row;
        });
    }

    public function headings(): array
    {
        return $this->includeSender
            ? ['Sender', 'Subject', 'Recipients', 'Status', 'Date']
            : ['Subject', 'Recipients', 'Status', 'Date'];
    }
}
