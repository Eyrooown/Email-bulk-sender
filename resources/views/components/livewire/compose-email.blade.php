<div>
    {{-- Recipients --}}
    <div class="mb-4">
        <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2">Recipients</p>
        <div class="bg-white border border-base-300 rounded-xl shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-4 py-3 bg-base-100 border-b border-base-300">
                <span class="text-sm font-semibold text-gray-700">To:</span>
                <button wire:click="openCsvModal" class="flex items-center gap-2 btn btn-xs btn-outline hover-clr-accent rounded-lg">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                    Import from CSV
                </button>
            </div>

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
                @error('recipients')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Chips --}}
            <div class="px-4 py-3 min-h-[52px]">
                <div class="flex flex-wrap gap-2 items-center">
                    @if(empty($recipients))
                        <span class="text-sm text-gray-300 italic">No recipients yet. Type an email above or import a CSV.</span>
                    @else
                        @foreach($visibleRecipients as $email)
                            <div class="badge badge-info gap-1 text-xs py-3 px-2">
                                <span class="w-4 h-4 rounded-full bg-blue-700 text-white text-[9px] font-bold flex items-center justify-center">{{ strtoupper($email[0]) }}</span>
                                {{ $email }}
                                <button wire:click="removeRecipient('{{ $email }}')" class="ml-1 hover:text-red-500">×</button>
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
    <input type="text" wire:model="subject" placeholder="Subject..." class="input input-bordered w-full mb-4" />

    {{-- Compose Editor --}}
    <div class="mb-4">
        <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2">Compose Message</p>
        <div class="bg-white border border-base-300 rounded-xl shadow-sm overflow-hidden focus-within:border-red-500 focus-within:shadow-md transition-all">

            {{-- Toolbar --}}
            <div class="flex items-center flex-wrap gap-4 px-3 py-2 bg-base-100 border-b border-base-300">
                <button type="button" onclick="fmt('bold')" class="btn btn-xs btn-ghost font-mono font-bold">B</button>
                <button type="button" onclick="fmt('italic')" class="btn btn-xs btn-ghost font-mono italic">I</button>
                <button type="button" onclick="fmt('underline')" class="btn btn-xs btn-ghost font-mono underline">U</button>
                <button type="button" onclick="fmt('strikeThrough')" class="btn btn-xs btn-ghost font-mono line-through">S</button>
                <div class="w-px h-5 bg-base-300 mx-1"></div>
                <button type="button" onclick="fmt('insertUnorderedList')" class="btn btn-xs btn-ghost">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="9" y1="6" x2="20" y2="6"/><line x1="9" y1="12" x2="20" y2="12"/><line x1="9" y1="18" x2="20" y2="18"/><circle cx="4" cy="6" r="1.5" fill="currentColor" stroke="none"/><circle cx="4" cy="12" r="1.5" fill="currentColor" stroke="none"/><circle cx="4" cy="18" r="1.5" fill="currentColor" stroke="none"/></svg>
                </button>
                <button type="button" onclick="fmt('insertOrderedList')" class="btn btn-xs btn-ghost">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="10" y1="6" x2="20" y2="6"/><line x1="10" y1="12" x2="20" y2="12"/><line x1="10" y1="18" x2="20" y2="18"/></svg>
                </button>
                <div class="w-px h-5 bg-base-300 mx-1"></div>
                <button type="button" onclick="fmt('justifyLeft')" class="btn btn-xs btn-ghost">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="15" y2="12"/><line x1="3" y1="18" x2="18" y2="18"/></svg>
                </button>
                <button type="button" onclick="fmt('justifyCenter')" class="btn btn-xs btn-ghost">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="3" y1="6" x2="21" y2="6"/><line x1="6" y1="12" x2="18" y2="12"/><line x1="4" y1="18" x2="20" y2="18"/></svg>
                </button>
                <button type="button" onclick="fmt('justifyRight')" class="btn btn-xs btn-ghost">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="3" y1="6" x2="21" y2="6"/><line x1="9" y1="12" x2="21" y2="12"/><line x1="6" y1="18" x2="21" y2="18"/></svg>
                </button>
                <div class="w-px h-5 bg-base-300 mx-1"></div>
                <button type="button" onclick="fmt('undo')" class="btn btn-xs btn-ghost">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.96"/></svg>
                </button>
                <button type="button" onclick="fmt('redo')" class="btn btn-xs btn-ghost">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-.49-3.96"/></svg>
                </button>
            </div>

            {{-- Editable Area --}}
            <div wire:ignore>
                <div
                    id="editor"
                    contenteditable="true"
                    data-placeholder="Start typing your message here..."
                    oninput="syncBody(this)"
                    class="p-5 min-h-[240px] outline-none text-sm leading-relaxed text-gray-800"
                ></div>
            </div>

            @error('body')
                <p class="text-xs text-red-500 px-5 pb-2">{{ $message }}</p>
            @enderror

            {{-- Attachments --}}
            @if(!empty($attachments))
                <div class="flex flex-wrap gap-2 px-4 py-2 border-t border-base-300">
                    @foreach($attachments as $i => $attachment)
                        <div class="badge badge-outline gap-1 text-xs">
                            📎 {{ $attachment->getClientOriginalName() }}
                            <button wire:click="removeAttachment({{ $i }})" class="ml-1 text-gray-400 hover:text-red-500">×</button>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Footer --}}
            <div class="flex items-center justify-between px-4 py-3 bg-base-100 border-t border-base-300">
                <div class="flex items-center gap-3">
                    <label class="btn btn-xs btn-ghost gap-1 hover-clr-accent cursor-pointer">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
                        Attach
                        <input type="file" wire:model="attachments" multiple class="hidden" />
                    </label>
                </div>
                <button wire:click="send" wire:loading.attr="disabled" x-on:click="document.getElementById('sendingModal').style.cssText='display:flex !important; position:fixed;'" class="btn btn-sm clr-bg-accent text-white rounded-lg gap-2 p-4">
                    <span wire:loading.remove wire:target="send">
                        <x-icons.send classes="w-3 h-3" />
                    </span>
                    <span wire:loading wire:target="send">
                        <span class="loading loading-spinner loading-xs"></span>
                    </span>
                    Send
                </button>
            </div>
        </div>
    </div>

    {{-- Recipients Table --}}
    @if(!empty($recipients))
        <div class="mb-4">
            <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2">Recipient List</p>
            <div class="bg-white border border-base-300 rounded-xl shadow-sm overflow-hidden">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th class="text-xs uppercase text-gray-400">#</th>
                            <th class="text-xs uppercase text-gray-400">Email Address</th>
                            <th class="text-xs uppercase text-gray-400">Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recipients as $i => $email)
                            <tr>
                                <td class="text-xs text-gray-400 font-mono">{{ $i + 1 }}</td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-blue-700 text-white text-xs font-bold flex items-center justify-center">{{ strtoupper($email[0]) }}</div>
                                        {{ $email }}
                                    </div>
                                </td>
                                <td>
                                    @php $status = $recipientStatuses[$email] ?? 'pending'; @endphp
                                    <span class="badge badge-sm {{ $status === 'sent' ? 'badge-success' : ($status === 'failed' ? 'badge-error' : 'badge-warning') }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                                <td>
                                    <button wire:click="removeRecipient('{{ $email }}')" class="btn btn-xs btn-ghost hover:text-red-500">×</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- CSV Modal --}}
    @if($showCsvModal)
        <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center">
            <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 bg-base-100 border-b border-base-300">
                    <strong class="text-base text-gray-800">Import Recipients from CSV</strong>
                    <button wire:click="closeCsvModal" class="btn btn-xs btn-ghost text-xl">×</button>
                </div>
                <div class="p-5">
                    @if(empty($csvHeaders))
                        <label class="border-2 border-dashed border-base-300 rounded-xl p-8 text-center cursor-pointer hover:border-red-400 hover:bg-red-50 transition-all mb-4 block">
                            <svg class="w-8 h-8 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="12" y2="12"/><line x1="15" y1="15" x2="12" y2="12"/></svg>
                            <p class="text-sm text-gray-500"><strong>Click to upload</strong> or drag & drop</p>
                            <span class="text-xs text-gray-300">CSV files only</span>
                            <input type="file" wire:model="csvFile" accept=".csv" class="hidden" />
                        </label>
                        <div wire:loading wire:target="csvFile" class="text-center text-sm text-gray-400">Parsing CSV...</div>
                    @else
                        <div class="mb-4">
                            <label class="text-xs font-semibold text-gray-400 uppercase block mb-1">Which column contains emails?</label>
                            <select wire:model="selectedEmailColumn" class="select select-sm select-bordered w-full">
                                <option value="">— Select column —</option>
                                @foreach($csvHeaders as $i => $header)
                                    <option value="{{ $i }}">{{ $header }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="table table-xs w-full mb-4">
                                <thead>
                                    <tr>
                                        @foreach($csvHeaders as $header)
                                            <th>{{ $header }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($previewRows as $row)
                                        <tr>
                                            @foreach($csvHeaders as $i => $header)
                                                <td>{{ $row[$i] ?? '' }}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                    @if(count($csvRows) > 5)
                                        <tr>
                                            <td colspan="{{ count($csvHeaders) }}" class="text-gray-400 italic text-xs">...and {{ count($csvRows) - 5 }} more rows</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                <div class="flex items-center justify-end gap-2 px-5 py-3 bg-base-100 border-t border-base-300">
                    <button wire:click="closeCsvModal" class="btn btn-sm btn-ghost">Cancel</button>
                    @if(!empty($csvHeaders) && $selectedEmailColumn !== '')
                        @php
                            $count = collect($csvRows)->filter(fn($row) => !empty($row[(int)$selectedEmailColumn]) && str_contains($row[(int)$selectedEmailColumn], '@'))->count();
                        @endphp
                        <button wire:click="importFromCsv" class="btn btn-sm clr-bg-accent text-white">
                            Import {{ $count }} Recipient{{ $count !== 1 ? 's' : '' }}
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- Sending Overlay --}}
    <div id="sendingModal" style="display:none !important" class="fixed inset-0 bg-black/40 z-[9999] items-center justify-center">
        <div class="bg-white rounded-2xl px-10 py-8 text-center shadow-2xl w-full max-w-sm">
            <div class="w-10 h-10 border-4 border-base-200 border-t-red-500 rounded-full animate-spin mx-auto mb-4"></div>
            <strong class="block text-lg text-gray-800 mb-2">Sending your email...</strong>
            <p class="text-sm clr-accent font-bold mb-3" id="sendProgressText">Preparing...</p>
            <div class="w-full bg-base-200 rounded-full h-2 mb-3">
                <div id="sendProgressBar" class="clr-bg-accent h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
            </div>
            <p class="text-xs text-gray-400 truncate" id="sendCurrentEmail"></p>
        </div>
    </div>

    {{-- Toast --}}
    @if($showToast)
        <div class="fixed bottom-8 right-8 z-50 bg-gray-900 text-white px-5 py-4 rounded-2xl shadow-2xl flex items-center gap-3">
            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                <svg class="w-3 h-3" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <div>
                <strong class="block text-sm mb-0.5">Email sent successfully!</strong>
                <span class="text-xs text-gray-400">{{ $toastMessage }}</span>
            </div>
            <button wire:click="dismissToast" class="ml-2 text-gray-400 hover:text-white">×</button>
        </div>
    @endif

    <script>
        function fmt(cmd, val) {
            document.execCommand(cmd, false, val || null);
            syncBody(document.getElementById('editor'));
        }

        function syncBody(el) {
            @this.set('body', el.innerHTML);
            const text = el.innerText.trim();
            const words = text ? text.split(/\s+/).length : 0;
            document.getElementById('wordCount').textContent = words + (words === 1 ? ' word' : ' words');
        }

        document.addEventListener('livewire:initialized', () => {
            const componentId = @this.id;

            window.Echo.channel('email-progress.' + componentId)
                .listen('.progress', (data) => {
                    const modal = document.getElementById('sendingModal');
                    const progressText = document.getElementById('sendProgressText');
                    const progressBar = document.getElementById('sendProgressBar');
                    const currentEmail = document.getElementById('sendCurrentEmail');

                    if (progressText) progressText.textContent = 'Sending ' + data.current + ' / ' + data.total;
                    if (progressBar) progressBar.style.width = ((data.current / data.total) * 100) + '%';
                    if (currentEmail) currentEmail.textContent = data.currentEmail !== 'done' ? data.currentEmail : '';

                    if (data.currentEmail === 'done') {
                        document.getElementById('sendingModal').style.cssText = 'display:none !important';

                        @this.set('showToast', true);
                        @this.set('toastMessage', 'Sent to ' + data.total + ' recipient' + (data.total > 1 ? 's' : ''));

                        setTimeout(() => {
                            window.location.href = '{{ route("dashboard") }}';
                        }, 2000);
                    }
                });
        });
    </script>
</div>
