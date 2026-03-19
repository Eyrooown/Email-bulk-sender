<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Emails Export</title>
    <style>
        table { width: 100%; border-collapse: collapse; font-size: 11px; }
        th, td { border: 1px solid #333; padding: 6px 8px; text-align: left; }
        th { background: #f0f0f0; font-weight: bold; }
        tr:nth-child(even) { background: #f9f9f9; }
    </style>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>#</th>
                @if(!empty($includeSender))
                    <th>Sender</th>
                @endif
                <th>Subject</th>
                <th>Recipients</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($emails as $index => $email)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    @if(!empty($includeSender))
                        <td>{{ $email->user->name ?? 'Unknown' }}</td>
                    @endif
                    <td>{{ $email->subject }}</td>
                    <td>{{ $email->recipients_count }} recipient{{ $email->recipients_count !== 1 ? 's' : '' }}</td>
                    <td>{{ ucfirst($email->status) }}</td>
                    <td>{{ $email->created_at->timezone('Asia/Manila')->format('M d, Y h:i A') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ !empty($includeSender) ? 6 : 5 }}" style="text-align: center; color: #666;">No emails to export.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
