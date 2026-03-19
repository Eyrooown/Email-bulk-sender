<?php

namespace App\Http\Controllers;

use App\Exports\EmailsExport;
use App\Models\Email;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class DashboardExportController extends Controller
{
    protected function getEmailsForExport(Request $request)
    {
        $query = Email::query()
            ->where('status', 'sent')
            ->with('user:id,name,email')
            ->withCount('recipients');

        if (!Auth::user()?->is_admin) {
            $query->where('user_id', Auth::id());
        }

        $from = $request->query('from');
        $to = $request->query('to');

        try {
            if ($from) {
                $query->where('created_at', '>=', Carbon::parse($from, 'Asia/Manila')->startOfDay()->utc());
            }
            if ($to) {
                $query->where('created_at', '<=', Carbon::parse($to, 'Asia/Manila')->endOfDay()->utc());
            }
        } catch (\Throwable $e) {
            // If invalid datetime is provided, ignore the filter.
        }

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
        $filename = 'emails-' . now()->timezone('Asia/Manila')->format('Y-m-d') . '.xlsx';

        return Excel::download(
            new EmailsExport($emails, Auth::user()?->is_admin === true),
            $filename,
            \Maatwebsite\Excel\Excel::XLSX
        );
    }

    public function pdf(Request $request)
    {
        $emails = $this->getEmailsForExport($request);
        $filename = 'emails-' . now()->timezone('Asia/Manila')->format('Y-m-d') . '.pdf';

        $pdf = Pdf::loadView('exports.emails-pdf', [
            'emails' => $emails,
            'includeSender' => Auth::user()?->is_admin === true,
        ])
            ->setPaper('a4', 'landscape');

        return response()->streamDownload(
            fn () => print($pdf->output()),
            $filename,
            ['Content-Type' => 'application/pdf']
        );
    }
}
