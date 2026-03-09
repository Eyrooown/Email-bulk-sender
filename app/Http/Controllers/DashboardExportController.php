<?php

namespace App\Http\Controllers;

use App\Exports\EmailsExport;
use App\Models\Email;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class DashboardExportController extends Controller
{
    protected function getEmailsForExport(Request $request)
    {
        $query = Email::where('user_id', Auth::id())
            ->where('status', 'sent')
            ->withCount('recipients');

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                    ->orWhere('body', 'like', "%{$search}%");
            });
        }

        match ($request->query('sortBy', 'date_desc')) {
            'subject_asc'  => $query->orderBy('subject', 'asc'),
            'subject_desc' => $query->orderBy('subject', 'desc'),
            'date_asc'     => $query->orderBy('created_at', 'asc'),
            'date_desc'    => $query->orderBy('created_at', 'desc'),
            default       => $query->orderBy('created_at', 'desc'),
        };

        return $query->get();
    }

    public function excel(Request $request)
    {
        $emails = $this->getEmailsForExport($request);
        $filename = 'emails-' . now()->format('Y-m-d-His') . '.xlsx';

        return Excel::download(new EmailsExport($emails), $filename, \Maatwebsite\Excel\Excel::XLSX);
    }

    public function pdf(Request $request)
    {
        $emails = $this->getEmailsForExport($request);
        $filename = 'emails-' . now()->format('Y-m-d-His') . '.pdf';

        $pdf = Pdf::loadView('exports.emails-pdf', ['emails' => $emails])
            ->setPaper('a4', 'landscape');

        return response()->streamDownload(
            fn () => print($pdf->output()),
            $filename,
            ['Content-Type' => 'application/pdf']
        );
    }
}
