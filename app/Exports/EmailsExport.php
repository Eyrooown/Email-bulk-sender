<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmailsExport implements FromCollection, WithHeadings
{
    public function __construct(
        protected Collection $emails
    ) {}

    public function collection(): Collection
    {
        return $this->emails->map(fn ($email) => [
            $email->subject,
            $email->recipients_count,
            ucfirst($email->status),
            $email->created_at->format('M d, Y'),
        ]);
    }

    public function headings(): array
    {
        return ['Subject', 'Recipients', 'Status', 'Date'];
    }
}
