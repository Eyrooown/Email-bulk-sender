<div>
    {{-- Back button + Edit button --}}
    <div class="flex items-center justify-between mb-4">
        <a href="{{ route('inbox') }}" class="btn btn-sm btn-ghost gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
            Back to Inbox
        </a>
        @if(!$editMode)
            <button wire:click="toggleEdit" class="btn btn-sm btn-outline hover-clr-accent rounded-lg gap-2">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Edit
            </button>
        @endif
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
                        <button wire:click="addRecipient" class="btn btn-sm clr-bg-accent text-white rounded-lg p-4">Add</button>
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
        @if($editMode)
            <input type="text" wire:model="subject" placeholder="Subject..." class="input input-bordered w-full mb-4" />
        @else
            <div class="input input-bordered w-full mb-4 flex items-center text-gray-700 bg-white cursor-default">
                {{ $email->subject ?: '(No Subject)' }}
            </div>
        @endif
    </div>

    {{-- Message Body --}}
    <div class="mb-4">
        <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2">Message</p>
        <div class="bg-white border border-base-300 rounded-xl shadow-sm overflow-hidden">

            @if($editMode)
                {{-- Editable: rich text editor with toolbar --}}
                <div x-data="richTextEditor({{ json_encode($body ?? '') }})">
                    {{-- Toolbar --}}
                    <div class="flex items-center flex-nowrap gap-1 px-2 py-1 bg-base-100 border-b border-base-300 overflow-x-auto">
                        <button type="button" @mousedown.prevent="format('bold')" class="btn btn-xs btn-ghost font-mono font-bold px-2 shrink-0">B</button>
                        <button type="button" @mousedown.prevent="format('italic')" class="btn btn-xs btn-ghost font-mono italic px-2 shrink-0">I</button>
                        <button type="button" @mousedown.prevent="format('underline')" class="btn btn-xs btn-ghost font-mono underline px-2 shrink-0">U</button>
                        <button type="button" @mousedown.prevent="format('strikeThrough')" class="btn btn-xs btn-ghost font-mono line-through px-2 shrink-0">S</button>
                        <div class="w-px h-5 bg-base-300 mx-1 shrink-0 hidden sm:block"></div>
                        <button type="button" @mousedown.prevent="format('insertUnorderedList')" class="btn btn-xs btn-ghost">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="9" y1="6" x2="20" y2="6"/><line x1="9" y1="12" x2="20" y2="12"/><line x1="9" y1="18" x2="20" y2="18"/><circle cx="4" cy="6" r="1.5" fill="currentColor" stroke="none"/><circle cx="4" cy="12" r="1.5" fill="currentColor" stroke="none"/><circle cx="4" cy="18" r="1.5" fill="currentColor" stroke="none"/></svg>
                        </button>
                        <button type="button" @mousedown.prevent="format('insertOrderedList')" class="btn btn-xs btn-ghost">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="10" y1="6" x2="20" y2="6"/><line x1="10" y1="12" x2="20" y2="12"/><line x1="10" y1="18" x2="20" y2="18"/></svg>
                        </button>
                        <div class="w-px h-5 bg-base-300 mx-1 shrink-0 hidden sm:block"></div>
                        <button type="button" @mousedown.prevent="format('justifyLeft')" class="btn btn-xs btn-ghost">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="15" y2="12"/><line x1="3" y1="18" x2="18" y2="18"/></svg>
                        </button>
                        <button type="button" @mousedown.prevent="format('justifyCenter')" class="btn btn-xs btn-ghost">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="3" y1="6" x2="21" y2="6"/><line x1="6" y1="12" x2="18" y2="12"/><line x1="4" y1="18" x2="20" y2="18"/></svg>
                        </button>
                        <button type="button" @mousedown.prevent="format('justifyRight')" class="btn btn-xs btn-ghost">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="3" y1="6" x2="21" y2="6"/><line x1="9" y1="12" x2="21" y2="12"/><line x1="6" y1="18" x2="21" y2="18"/></svg>
                        </button>
                        <div class="w-px h-5 bg-base-300 mx-1 shrink-0 hidden sm:block"></div>
                        <button type="button" @mousedown.prevent="format('undo')" class="btn btn-xs btn-ghost">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.96"/></svg>
                        </button>
                        <button type="button" @mousedown.prevent="format('redo')" class="btn btn-xs btn-ghost">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-.49-3.96"/></svg>
                        </button>
                    </div>

                    {{-- Editable Area --}}
                    <div wire:ignore>
                        <div
                            id="email-detail-editor-body"
                            x-ref="editor"
                            contenteditable="true"
                            data-placeholder="Start typing your message here..."
                            class="prose-editor p-5 min-h-[240px] outline-none text-sm leading-relaxed text-gray-800 [&_ul]:list-disc [&_ul]:pl-6 [&_ul]:my-2 [&_ol]:list-decimal [&_ol]:pl-6 [&_ol]:my-2 [&_li]:my-0.5"
                        >{!! $body ?? '' !!}</div>
                    </div>
                </div>
            @else
                {{-- Read-only: no toolbar --}}
                <div class="p-5 min-h-[240px] text-sm leading-relaxed text-gray-800">
                    {!! $email->body !!}
                </div>
            @endif

            {{-- Attachments --}}
            @if($editMode)
                <div class="flex flex-wrap gap-2 px-4 py-2 border-t border-base-300 items-center">
                    @foreach($email->attachments as $attachment)
                        @if(!in_array($attachment->id, $removedAttachmentIds))
                            <div class="badge badge-outline gap-1 text-xs flex items-center">
                                <a href="{{ asset('storage/' . $attachment->path) }}" target="_blank" rel="noopener" class="hover:underline flex items-center gap-1" title="View file">
                                    📎 {{ $attachment->filename }}
                                </a>
                                <button type="button" wire:click="removeExistingAttachment({{ $attachment->id }})" class="ml-1 text-gray-400 hover:text-red-500">×</button>
                            </div>
                        @endif
                    @endforeach
                    @foreach($attachments ?? [] as $i => $attachment)
                        <div class="badge badge-outline gap-1 text-xs flex items-center">
                            <a href="{{ $attachment->temporaryUrl() }}" target="_blank" rel="noopener" class="hover:underline flex items-center gap-1" title="View file">
                                📎 {{ $attachment->getClientOriginalName() }}
                            </a>
                            <button type="button" wire:click="removeNewAttachment({{ $i }})" class="ml-1 text-gray-400 hover:text-red-500">×</button>
                        </div>
                    @endforeach
                    <label class="btn btn-xs btn-ghost gap-1 hover-clr-accent cursor-pointer">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
                        Attach
                        <input type="file" wire:model="attachments" multiple class="hidden" />
                    </label>
                    @if(empty($email->attachments->whereNotIn('id', $removedAttachmentIds)) && empty($attachments))
                        <span class="text-xs text-gray-400">No attachments</span>
                    @endif
                </div>
            @elseif($email->attachments->count() > 0)
                <div class="flex flex-wrap gap-2 px-4 py-2 border-t border-base-300">
                    @foreach($email->attachments as $attachment)
                        <a href="{{ asset('storage/' . $attachment->path) }}" target="_blank" rel="noopener" class="badge badge-outline gap-1 text-xs hover:underline" title="View file">📎 {{ $attachment->filename }}</a>
                    @endforeach
                </div>
            @endif

            {{-- Footer --}}
            <div class="flex items-center justify-between px-4 py-3 bg-base-100 border-t border-base-300">
                <div class="flex items-center gap-3">
                    <span class="text-xs text-gray-400 font-mono">{{ str_word_count(strip_tags($editMode ? ($body ?? '') : $email->body)) }} words</span>
                    <div class="w-px h-4 bg-base-300"></div>
                    <span class="text-xs text-gray-400">{{ $email->created_at->format('M d, Y h:i A') }}</span>
                </div>
                @if($editMode)
                    <button x-data
                            @click="const editor = document.getElementById('email-detail-editor-body'); const html = editor?.innerHTML ?? ''; $wire.call('sendAgain', html)"
                            wire:loading.attr="disabled"
                            wire:target="sendAgain"
                            class="btn btn-sm clr-bg-accent text-white rounded-lg gap-2 p-4">
                        <span wire:loading.remove wire:target="sendAgain">Send</span>
                        <span wire:loading wire:target="sendAgain">Sending...</span>
                    </button>
                @else
                    <span class="badge {{ $email->status === 'sent' ? 'clr-bg-accent' : 'clr-bg-accent' }} badge-lg text-white">
                        {{ ucfirst($email->status) }}
                    </span>
                @endif
            </div>
        </div>
    </div>

    {{-- Recipients Table --}}
    <div class="mb-4">
        <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2">Recipient List</p>
        <div class="bg-white border border-base-300 rounded-xl shadow-sm overflow-hidden">
            {{-- Mobile: collapsible list --}}
            <div class="md:hidden divide-y divide-base-200">
                @forelse($recipientsPaginator as $recipient)
                    @php
                        $recipientEmail = is_object($recipient) ? $recipient->email : $recipient;
                        $status = is_object($recipient) ? $recipient->status : ($email->recipients->firstWhere('email', $recipient)?->status ?? 'pending');
                        $createdAt = is_object($recipient) ? $recipient->created_at : ($email->recipients->firstWhere('email', $recipient)?->created_at);
                    @endphp
                    <div x-data="{ expanded: false }" class="bg-white">
                        <div class="flex items-center justify-between gap-3 py-3 px-4">
                            <div class="flex items-center gap-2 min-w-0 flex-1">
                                <div class="w-7 h-7 shrink-0 rounded-full bg-blue-700 text-white text-xs font-bold flex items-center justify-center">
                                    {{ strtoupper($recipientEmail[0]) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="text-sm whitespace-nowrap truncate">
                                        {{ $recipientEmail }}
                                    </div>
                                </div>
                            </div>
                            <button type="button" @click="expanded = !expanded" class="btn btn-ghost btn-sm btn-circle shrink-0">
                                <svg x-show="!expanded" class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
                                <svg x-show="expanded" x-cloak class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 15l7-7 7 7"/></svg>
                            </button>
                        </div>
                        <div x-show="expanded" x-transition class="px-4 pb-4 pt-0 space-y-2">
                            <div class="flex items-center justify-between gap-3">
                                <span class="badge badge-sm {{ $status === 'sent' ? 'badge-success' : ($status === 'failed' ? 'badge-error' : 'badge-warning') }}">
                                    {{ ucfirst($status) }}
                                </span>
                                @if($editMode)
                                    <button wire:click="removeRecipient('{{ $recipientEmail }}')" class="btn btn-xs btn-ghost hover:text-red-500 shrink-0">Remove</button>
                                @endif
                            </div>
                            <div class="text-xs text-gray-400">
                                {{ $createdAt?->format('M d, Y h:i A') ?? '—' }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-gray-400 italic">No recipients found.</div>
                @endforelse
            </div>

            {{-- Desktop: table --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="table w-full min-w-[640px]">
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
                                    <div class="flex items-center gap-2 min-w-0">
                                        <div class="w-7 h-7 rounded-full bg-blue-700 text-white text-xs font-bold flex items-center justify-center">{{ strtoupper($recipientEmail[0]) }}</div>
                                        <span class="min-w-0 flex-1 text-sm whitespace-nowrap truncate">{{ $recipientEmail }}</span>
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
            </div>
            @if($recipientsPaginator->hasPages())
                <div class="p-2 border-t border-base-200">
                    {{ $recipientsPaginator->links('livewire::tailwind') }}
                </div>
            @endif
        </div>
    </div>

    {{-- Sending Modal --}}
    @if($showSendingModal)
        <div wire:key="sending-progress-modal" wire:poll.500ms="checkSendingProgress" class="fixed inset-0 bg-black/40 z-[9999] flex items-center justify-center">
            <div class="bg-white rounded-2xl px-10 py-8 text-center shadow-2xl w-full max-w-sm">
                <div class="w-10 h-10 border-4 border-base-200 border-t-red-500 rounded-full animate-spin mx-auto mb-4"></div>
                <strong class="block text-lg text-gray-800 mb-2">Sending your email...</strong>
                <p class="text-sm clr-accent font-bold mb-3">{{ $sendCurrent }}/{{ $sendTotal }} recipients</p>
                <div class="w-full bg-base-200 rounded-full h-2 mb-3">
                    <div class="clr-bg-accent h-2 rounded-full transition-all duration-300" style="width: {{ $sendTotal > 0 ? ($sendCurrent / $sendTotal) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>
    @endif
</div>
