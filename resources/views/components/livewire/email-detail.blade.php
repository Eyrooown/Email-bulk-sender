<div>
    {{-- Back button + Edit button --}}
    <div class="flex items-center justify-between mb-4">
        <a href="{{ route('dashboard') }}" class="btn btn-sm btn-ghost gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
            Back to Dashboard
        </a>
        <div class="flex gap-2">
            @if($editMode)
                <button wire:click="toggleEdit" class="btn btn-sm btn-ghost">Cancel</button>
                <button wire:click="saveChanges" class="btn btn-sm p-2 clr-bg-accent text-white rounded-lg">Save Changes</button>
            @else
                <button wire:click="toggleEdit" class="btn btn-sm btn-outline hover-clr-accent rounded-lg gap-2">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    Edit
                </button>
            @endif
        </div>
    </div>

    {{-- Recipients --}}
    <div class="mb-4">
        <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2">Recipients</p>
        <div class="bg-white border border-base-300 rounded-xl shadow-sm overflow-hidden">

            @if($editMode)
                {{-- Manual Input --}}
                <div class="px-4 py-3 border-b border-base-300">
                    <div class="flex items-center gap-2">
                        <input
                            type="email"
                            wire:model="manualEmail"
                            wire:keydown.enter="addRecipient"
                            placeholder="Type an email and press Enter or click Add..."
                            class="input input-sm input-bordered flex-1 focus:outline-none focus:border-base-300"
                        />
                        <button wire:click="addRecipient" class="btn btn-sm clr-bg-accent text-white rounded-lg">Add</button>
                    </div>
                    @if($manualEmailError)
                        <p class="text-xs text-red-500 mt-1">{{ $manualEmailError }}</p>
                    @endif
                </div>
            @endif

            {{-- Chips --}}
            <div class="px-4 py-3 min-h-[52px]">
                <div class="flex flex-wrap gap-2 items-center">
                    @if(empty($recipients))
                        <span class="text-sm text-gray-300 italic">No recipients.</span>
                    @else
                        @foreach($visibleRecipients as $recipientEmail)
                            <div class="badge badge-info gap-1 text-xs py-3 px-2">
                                <span class="w-4 h-4 rounded-full bg-blue-700 text-white text-[9px] font-bold flex items-center justify-center">{{ strtoupper($recipientEmail[0]) }}</span>
                                {{ $recipientEmail }}
                                @if($editMode)
                                    <button wire:click="removeRecipient('{{ $recipientEmail }}')" class="ml-1 hover:text-red-500">×</button>
                                @endif
                            </div>
                        @endforeach

                        @if(!$showAllRecipients && count($recipients) > 4)
                            <div wire:click="$set('showAllRecipients', true)" class="badge badge-ghost cursor-pointer text-xs py-3">
                                +{{ count($recipients) - 4 }} more
                            </div>
                        @endif

                        @if($showAllRecipients && count($recipients) > 4)
                            <div wire:click="$set('showAllRecipients', false)" class="badge badge-ghost cursor-pointer text-xs py-3">
                                Show less
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Subject --}}
    <div>
        <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2">Subject</p>
        <div class="input input-bordered w-full mb-4 flex items-center text-gray-700 bg-white cursor-default">
            {{ $email->subject ?: '(No Subject)' }}
        </div>
    </div>

    {{-- Message Body --}}
    <div class="mb-4">
        <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2">Message</p>
        <div class="bg-white border border-base-300 rounded-xl shadow-sm overflow-hidden">

            {{-- Toolbar --}}
            <div class="flex items-center flex-wrap gap-4 px-3 py-2 bg-base-100 border-b border-base-300 opacity-40 pointer-events-none select-none">
                <button class="btn btn-xs btn-ghost font-mono font-bold">B</button>
                <button class="btn btn-xs btn-ghost font-mono italic">I</button>
                <button class="btn btn-xs btn-ghost font-mono underline">U</button>
                <button class="btn btn-xs btn-ghost font-mono line-through">S</button>
                <div class="w-px h-5 bg-base-300 mx-1"></div>
                <button class="btn btn-xs btn-ghost">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="9" y1="6" x2="20" y2="6"/><line x1="9" y1="12" x2="20" y2="12"/><line x1="9" y1="18" x2="20" y2="18"/></svg>
                </button>
                <button class="btn btn-xs btn-ghost">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="10" y1="6" x2="20" y2="6"/><line x1="10" y1="12" x2="20" y2="12"/><line x1="10" y1="18" x2="20" y2="18"/></svg>
                </button>
                <div class="w-px h-5 bg-base-300 mx-1"></div>
                <button class="btn btn-xs btn-ghost">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="15" y2="12"/><line x1="3" y1="18" x2="18" y2="18"/></svg>
                </button>
                <button class="btn btn-xs btn-ghost">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="3" y1="6" x2="21" y2="6"/><line x1="6" y1="12" x2="18" y2="12"/><line x1="4" y1="18" x2="20" y2="18"/></svg>
                </button>
                <button class="btn btn-xs btn-ghost">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="3" y1="6" x2="21" y2="6"/><line x1="9" y1="12" x2="21" y2="12"/><line x1="6" y1="18" x2="21" y2="18"/></svg>
                </button>
                <div class="w-px h-5 bg-base-300 mx-1"></div>
                <button class="btn btn-xs btn-ghost">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.96"/></svg>
                </button>
                <button class="btn btn-xs btn-ghost">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-.49-3.96"/></svg>
                </button>
            </div>

            {{-- Body --}}
            <div class="p-5 min-h-[240px] text-sm leading-relaxed text-gray-800">
                {!! $email->body !!}
            </div>

            {{-- Attachments --}}
            @if($email->attachments->count() > 0)
                <div class="flex flex-wrap gap-2 px-4 py-2 border-t border-base-300">
                    @foreach($email->attachments as $attachment)
                        <div class="badge badge-outline gap-1 text-xs">📎 {{ $attachment->filename }}</div>
                    @endforeach
                </div>
            @endif

            {{-- Footer --}}
            <div class="flex items-center justify-between px-4 py-3 bg-base-100 border-t border-base-300">
                <div class="flex items-center gap-3">
                    <span class="text-xs text-gray-400 font-mono">{{ str_word_count(strip_tags($email->body)) }} words</span>
                    <div class="w-px h-4 bg-base-300"></div>
                    <span class="text-xs text-gray-400">{{ $email->created_at->format('M d, Y h:i A') }}</span>
                </div>
                <span class="badge {{ $email->status === 'sent' ? 'badge-success' : 'badge-error' }} badge-lg">
                    {{ ucfirst($email->status) }}
                </span>
            </div>
        </div>
    </div>

    {{-- Recipients Table --}}
    <div class="mb-4">
        <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2">Recipient List</p>
        <div class="bg-white border border-base-300 rounded-xl shadow-sm overflow-hidden">
            <table class="table w-full">
                <thead>
                    <tr>
                        <th class="text-xs uppercase text-gray-400">#</th>
                        <th class="text-xs uppercase text-gray-400">Email Address</th>
                        <th class="text-xs uppercase text-gray-400">Status</th>
                        <th class="text-xs uppercase text-gray-400">Date</th>
                        @if($editMode)<th></th>@endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($recipientsPaginator as $recipient)
                        @php
                            $recipientEmail = is_object($recipient) ? $recipient->email : $recipient;
                            $status = is_object($recipient) ? $recipient->status : ($email->recipients->firstWhere('email', $recipient)?->status ?? 'pending');
                            $createdAt = is_object($recipient) ? $recipient->created_at : ($email->recipients->firstWhere('email', $recipient)?->created_at);
                        @endphp
                        <tr>
                            <td class="text-xs text-gray-400 font-mono">{{ $recipientsPaginator->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-blue-700 text-white text-xs font-bold flex items-center justify-center">{{ strtoupper($recipientEmail[0]) }}</div>
                                    {{ $recipientEmail }}
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-sm {{ $status === 'sent' ? 'badge-success' : ($status === 'failed' ? 'badge-error' : 'badge-warning') }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>
                            <td class="text-xs text-gray-400">{{ $createdAt?->format('M d, Y h:i A') ?? '—' }}</td>
                            @if($editMode)
                                <td><button wire:click="removeRecipient('{{ $recipientEmail }}')" class="btn btn-xs btn-ghost hover:text-red-500">×</button></td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-gray-400 italic py-6">No recipients found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @if($recipientsPaginator->hasPages())
                <div class="p-2 border-t border-base-200">
                    {{ $recipientsPaginator->links('livewire::tailwind') }}
                </div>
            @endif
        </div>
    </div>
</div>
