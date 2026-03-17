<div x-data="composePage()" @compose-dirty.window="dirty = true" @compose-saved.window="dirty = false">
    {{-- Recipients --}}
    <div class="mb-4">
        <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2">Recipients</p>
        <div class="bg-white border border-base-300 rounded-xl shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-4 py-3 bg-base-100 border-b border-base-300">
                <span class="text-sm font-semibold text-gray-700">To:</span>
                <button wire:click="openCsvModal" class="flex items-center gap-2 btn btn-xs btn-outline clr-bg-accent text-white hover-clr-bg-accent-light rounded-lg p-4">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                    Import
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
                    <button wire:click="addRecipient" @click="$dispatch('compose-dirty')" class="btn btn-sm clr-bg-accent text-white rounded-lg p-4 hover-clr-bg-accent-light">Add</button>
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
                            <div wire:key="chip-{{ $email }}" class="badge badge-info gap-1 text-xs py-3 px-2">
                                <span class="w-4 h-4 rounded-full bg-blue-700 text-white text-[9px] font-bold flex items-center justify-center">{{ strtoupper($email[0]) }}</span>
                                {{ $email }}
                                <button type="button" wire:click="removeRecipient('{{ $email }}')" class="ml-1 hover:text-red-500">×</button>
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
    <input type="text" wire:model="subject" @input="dirty = true" placeholder="Subject..." class="input input-bordered w-full mb-4" />

    {{-- Compose Editor (simple rich text) --}}
    <div class="mb-4" x-data="richTextEditor({{ json_encode($body ?? '') }})">
        <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2">Compose Message</p>

        @if(!empty($csvHeaders))
            @php
                $variableTokens = collect($csvHeaders)
                    ->filter(fn($h) => trim($h) !== '')
                    ->mapWithKeys(fn($h) => [$h => '{{'.$h.'}}']);
            @endphp
            <div class="px-3 py-2 mb-1 rounded-xl clr-primary text-base-100">
                <p class="text-xs mb-1 text-base-100 font-semibold">Note:
                    Use these variables to display the recipients' information.
                </p>
                <div class="flex flex-wrap items-center gap-1.5 mt-1">
                    <span class="text-[10px] font-semibold uppercase tracking-widest shrink-0 mr-1">
                        Variables:
                    </span>
                    @foreach($variableTokens as $header => $token)
                        <button
                            type="button"
                            class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-mono font-semibold clr-bg-secondary text-base-100 border transition-colors cursor-pointer"
                            x-data
                            x-on:click="
                                const editor = document.getElementById('compose-editor-body');
                                if (!editor) return;
                                editor.focus();
                                const selection = window.getSelection();
                                let range = selection && selection.rangeCount ? selection.getRangeAt(0) : null;
                                const textNode = document.createTextNode('{{ $token }}');
                                if (range && editor.contains(range.commonAncestorContainer)) {
                                    range.deleteContents();
                                    range.insertNode(textNode);
                                    range.setStartAfter(textNode);
                                    range.setEndAfter(textNode);
                                    selection.removeAllRanges();
                                    selection.addRange(range);
                                } else {
                                    editor.appendChild(textNode);
                                }
                                window.dispatchEvent(new CustomEvent('compose-dirty'));
                            "
                        >
                            {{ $token }}
                        </button>
                    @endforeach
                </div>
            </div>
        @endif
        <div class="bg-white border border-base-300 rounded-xl shadow-sm overflow-hidden focus-within:border-red-500 focus-within:shadow-md transition-all">

            {{-- Formatting toolbar --}}
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
                    id="compose-editor-body"
                    x-ref="editor"
                    contenteditable="true"
                    data-placeholder="Start typing your message here..."
                    @input="$dispatch('compose-dirty')"
                    class="prose-editor p-5 min-h-[360px] outline-none text-sm leading-relaxed text-gray-800 [&_ul]:list-disc [&_ul]:pl-6 [&_ul]:my-2 [&_ol]:list-decimal [&_ol]:pl-6 [&_ol]:my-2 [&_li]:my-0.5"
                ></div>
            </div>

            @error('body')
                <p class="text-xs text-red-500 px-5 pb-2">{{ $message }}</p>
            @enderror

            {{-- Attachments --}}
            @if(!empty($attachments))
                <div class="flex flex-wrap gap-2 px-4 py-2 border-t border-base-300">
                    @foreach($attachments as $i => $attachment)
                        <div class="badge badge-outline gap-1 text-xs flex items-center">
                            <a href="{{ $attachment->temporaryUrl() }}" target="_blank" rel="noopener" class="hover:underline flex items-center gap-1" title="View file">
                                📎 {{ $attachment->getClientOriginalName() }}
                            </a>
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
                <button @click="sendEmail()" wire:loading.attr="disabled" wire:target="sendWithBody" class="btn btn-sm clr-bg-accent text-white rounded-lg gap-2 p-4 hover-clr-bg-accent-light">
                    <span wire:loading.remove wire:target="sendWithBody">
                        <x-icons.send classes="w-3 h-3" />
                    </span>
                    <span wire:loading wire:target="sendWithBody">
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
                <div class="px-4 py-3 border-b border-base-300">
                    <input
                        type="search"
                        wire:model.live.debounce.300ms="recipientsSearch"
                        placeholder="Search recipients..."
                        class="input input-sm input-bordered w-full focus:outline-none focus:border-base-300"
                    />
                </div>
                <div class="overflow-auto" style="max-height: 40vh;">
                    {{-- Mobile: collapsible list --}}
                    <div class="md:hidden divide-y divide-base-200">
                        @foreach($recipientsPaginator as $i => $email)
                            @php $status = $recipientStatuses[$email] ?? 'pending'; @endphp
                            <div x-data="{ expanded: false }" class="bg-white">
                                <div class="flex items-center justify-between gap-3 py-3 px-4">
                                    <div class="flex items-center gap-2 min-w-0 flex-1">
                                        <div class="w-7 h-7 shrink-0 rounded-full bg-blue-700 text-white text-xs font-bold flex items-center justify-center">
                                            {{ strtoupper($email[0]) }}
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="min-w-0">
                                                <span class="block text-sm whitespace-nowrap truncate">
                                                    {{ $email }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" @click="expanded = !expanded" class="btn btn-ghost btn-sm btn-circle shrink-0">
                                        <svg x-show="!expanded" class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
                                        <svg x-show="expanded" x-cloak class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 15l7-7 7 7"/></svg>
                                    </button>
                                </div>
                                <div x-show="expanded" x-transition class="px-4 pb-4 pt-0">
                                    <div class="flex items-center justify-between gap-3">
                                        <span class="badge badge-sm {{ $status === 'sent' ? 'badge-success' : ($status === 'failed' ? 'badge-error' : 'badge-warning') }}">
                                            {{ ucfirst($status) }}
                                        </span>
                                        <button wire:click="removeRecipient('{{ $email }}')" class="btn btn-xs btn-ghost hover:text-red-500 shrink-0">Remove</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Desktop: table --}}
                    <div class="hidden md:block">
                        <table class="table w-full min-w-[520px]">
                            <thead>
                                <tr>
                                    <th class="text-xs uppercase text-gray-400">#</th>
                                    <th class="text-xs uppercase text-gray-400">Email Address</th>
                                    <th class="text-xs uppercase text-gray-400">Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recipientsPaginator as $i => $email)
                                    <tr>
                                        <td class="text-xs text-gray-400 font-mono">{{ $recipientsPaginator->firstItem() + $i }}</td>
                                        <td>
                                            <div class="flex items-center gap-2 min-w-0">
                                                <div class="w-7 h-7 rounded-full bg-blue-700 text-white text-xs font-bold flex items-center justify-center">{{ strtoupper($email[0]) }}</div>
                                                <span class="min-w-0 flex-1 text-sm whitespace-nowrap truncate">{{ $email }}</span>
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
                @if($recipientsPaginator->hasPages())
                    <div class="p-2 border-t border-base-200">
                        {{ $recipientsPaginator->links('livewire::tailwind') }}
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- CSV Modal --}}
    <div x-show="$wire.showCsvModal"
        x-transition
        class="fixed inset-0 bg-black/40 z-[9999] flex items-center justify-center">
        <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 bg-base-100 border-b border-base-300">
                <strong class="text-base text-gray-800">Import Recipients from CSV</strong>
                <button wire:click="closeCsvModal" class="btn btn-xs btn-ghost text-xl">×</button>
            </div>
            <div class="p-5">
                @if(!empty($csvParseError))
                    <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-sm text-red-700">
                        {{ $csvParseError }}
                    </div>
                @endif
                @error('csvFile')
                    <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-sm text-red-700">
                        {{ $message }}
                    </div>
                @enderror
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
                        <select wire:model="selectedEmailColumn" class="select h-10 select-sm select-bordered w-full">
                            <option value="">— Select column —</option>
                            @foreach($csvHeaders as $i => $header)
                                <option value="{{ $i }}">{{ $header }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4 p-3 rounded-lg bg-amber-50 border border-amber-200">
                        <p class="text-xs font-semibold text-amber-600 mb-1.5">
                            You can use these columns as variables in your message:
                        </p>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($csvHeaders as $header)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-mono font-semibold bg-amber-100 text-amber-700 border border-amber-300">
                                    &#123;&#123;{{ $header }}&#125;&#125;
                                </span>
                            @endforeach
                        </div>
                        <p class="mt-1 text-[11px] text-amber-700">
                            Example: type <code class="px-1 py-0.5 rounded bg-white border border-amber-200 font-mono text-[11px]">&#123;&#123;name&#125;&#125;</code> in the editor and it will be replaced per row when you send.
                        </p>
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
                    <button wire:click="importFromCsv" @click="$dispatch('compose-dirty')" class="btn btn-sm clr-bg-accent text-white p-4">
                        Import {{ $count }} Recipient{{ $count !== 1 ? 's' : '' }}
                    </button>
                @endif
            </div>
        </div>
    </div>

    {{-- Unsaved changes modal --}}
    <div x-show="showModal" x-cloak x-transition class="fixed inset-0 bg-black/40 z-[9998] flex items-center justify-center">
        <div class="bg-white rounded-2xl px-6 py-5 shadow-2xl w-full max-w-sm">
            <p class="text-gray-800 font-medium mb-4">You have unsaved changes. Save to drafts before leaving?</p>
            <div class="flex gap-3 justify-end">
                <button @click="cancelNavigation()" class="btn btn-ghost">Cancel</button>
                <button @click="discardAndNavigate()" class="btn clr-bg-accent text-white p-4">Discard</button>
                <button @click="saveAndNavigate()" class="btn clr-bg-accent text-white p-4">Save to Draft</button>
            </div>
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

</div>
