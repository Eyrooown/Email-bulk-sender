<?php

namespace App\Http\Controllers;

use App\Mail\BulkEmail;
use App\Models\Email;
use App\Models\EmailAttachment;
use App\Models\EmailRecipient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function index()
    {
        return view('inbox');
    }

    public function compose()
    {
        return view('compose');
    }

    public function show(Email $email)
    {
        $email->load('recipients', 'attachments');

        return view('recepients', compact('email'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'recipients' => 'required|array|min:1',
            'recipients.*' => 'email',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240',
        ]);

        // Create email record
        $email = Email::create([
            'user_id' => Auth::id(),
            'subject' => $request->subject ?? '(No Subject)',
            'body' => $request->body,
            'status' => 'sent',
        ]);

        // Save recipients and send
        foreach ($request->recipients as $recipient) {
            try {
                $mailable = new BulkEmail(
                    subject: $request->subject ?? '(No Subject)',
                    body: $request->body,
                    fromAddress: Auth::user()?->email,   // ← was null before
                    fromName: Auth::user()?->name,
                    replyToEmail: Auth::user()?->email    // ← optional, keep if you want reply-to
                );

                // Attach files if any
                if ($request->hasFile('attachments')) {
                    foreach ($request->file('attachments') as $file) {
                        $mailable->attach($file->getRealPath(), [
                            'as' => $file->getClientOriginalName(),
                            'mime' => $file->getMimeType(),
                        ]);
                    }
                }

                Mail::to($recipient)->send($mailable);

                EmailRecipient::create([
                    'email_id' => $email->id,
                    'email' => $recipient,
                    'status' => 'sent',
                ]);

            } catch (\Exception $e) {
                EmailRecipient::create([
                    'email_id' => $email->id,
                    'email' => $recipient,
                    'status' => 'failed',
                ]);
            }
        }

        // Save attachments to DB
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('email_attachments', 'public');
                EmailAttachment::create([
                    'email_id' => $email->id,
                    'filename' => $file->getClientOriginalName(),
                    'path' => $path,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Emails sent successfully!',
        ]);
    }
}
