<div class="flex flex-col min-h-0 flex-1" x-data="{ confirmId: null, confirmBulk: false }">
    {{-- Confirm single delete modal --}}
    <div x-show="confirmId !== null" x-cloak
         class="fixed inset-0 z-[100] flex items-center justify-center bg-black/40 backdrop-blur-sm"
         @keydown.escape.window="confirmId = null">
        <div class="bg-base-100 rounded-2xl shadow-xl p-6 w-80 max-w-[90vw] space-y-4">
            <h3 class="text-lg font-semibold">Move to Archive?</h3>
            <p class="text-sm text-gray-500">This email will be moved to the Archive. You can restore it anytime.</p>
            <div class="flex gap-3 justify-end">
                <button type="button" @click="confirmId = null" class="btn btn-ghost btn-sm">Cancel</button>
                <button type="button" @click="$wire.deleteEmail(confirmId); confirmId = null" class="btn clr-bg-accent btn-sm text-base-100 p-2">Move to Archive</button>
            </div>
        </div>
    </div>

    {{-- Confirm bulk delete modal --}}
    <div x-show="confirmBulk" x-cloak
         class="fixed inset-0 z-[100] flex items-center justify-center bg-black/40 backdrop-blur-sm"
         @keydown.escape.window="confirmBulk = false">
        <div class="bg-base-100 rounded-2xl shadow-xl p-6 w-80 max-w-[90vw] space-y-4">
            <h3 class="text-lg font-semibold">Move Selected to Archive?</h3>
            <p class="text-sm text-gray-500">All selected emails will be moved to the Archive. You can restore them anytime.</p>
            <div class="flex gap-3 justify-end">
                <button type="button" @click="confirmBulk = false" class="btn btn-ghost btn-sm">Cancel</button>
                <button type="button" @click="$wire.deleteSelected(); confirmBulk = false" class="btn clr-bg-accent btn-sm text-base-100 p-2">Move to Archive</button>
            </div>
        </div>
    </div>

    {{-- Top bar --}}
    <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between p-2 flex-shrink-0">
        {{-- Left group: Filter + Search + Export + Delete All --}}
        <div class="flex items-center gap-2 w-full md:w-auto">
            <div class="dropdown dropdown-bottom shrink-0">
                <button tabindex="0" type="button" class="btn md:w-36 border-2 border-black rounded-xl hover-clr-bg-accent">
                    <x-icons.sort class="w-4 h-4 inline-block" /> Filter
                </button>
                <div tabindex="-1" class="dropdown-content z-50 mt-2 w-[22rem] max-w-[90vw] rounded-2xl bg-base-100 p-4 shadow-xl border border-base-200">
                    <div class="flex items-center justify-between mb-3">
                        <div class="font-semibold">Filters</div>
                        <button type="button" wire:click="resetFilters" class="btn btn-ghost btn-sm">Reset</button>
                    </div>

                    <div class="space-y-4">
                        {{-- Sort --}}
                        <div>
                            <div class="text-xs uppercase tracking-widest text-gray-400 mb-2">Sort</div>
                            <div class="space-y-2">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" class="radio radio-sm" wire:model.live="sortBy" value="subject_asc" />
                                    <span class="text-sm">Alphabetical (A → Z)</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" class="radio radio-sm" wire:model.live="sortBy" value="subject_desc" />
                                    <span class="text-sm">Alphabetical (Z → A)</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" class="radio radio-sm" wire:model.live="sortBy" value="date_desc" />
                                    <span class="text-sm">Date (Newest first)</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" class="radio radio-sm" wire:model.live="sortBy" value="date_asc" />
                                    <span class="text-sm">Date (Oldest first)</span>
                                </label>
                            </div>
                        </div>

                        {{-- Date --}}
                        <div>
                            <div class="text-xs uppercase tracking-widest text-gray-400 mb-2">Date</div>
                            <div class="grid grid-cols-2 gap-2">
                                <label class="form-control w-full">
                                    <span class="label-text text-xs text-gray-500">From</span>
                                    <input type="date" class="input input-sm input-bordered w-full" wire:model.live="dateFrom" />
                                </label>
                                <label class="form-control w-full">
                                    <span class="label-text text-xs text-gray-500">To</span>
                                    <input type="date" class="input input-sm input-bordered w-full" wire:model.live="dateTo" />
                                </label>
                            </div>
                        </div>

                        {{-- Recipients --}}
                        <div>
                            <div class="text-xs uppercase tracking-widest text-gray-400 mb-2">Recipients</div>
                            <div class="grid grid-cols-2 gap-2">
                                <label class="form-control w-full">
                                    <span class="label-text text-xs text-gray-500">Min</span>
                                    <input type="number" min="0" inputmode="numeric" class="input input-sm input-bordered w-full" wire:model.live="recipientsMin" placeholder="0" />
                                </label>
                                <label class="form-control w-full">
                                    <span class="label-text text-xs text-gray-500">Max</span>
                                    <input type="number" min="0" inputmode="numeric" class="input input-sm input-bordered w-full" wire:model.live="recipientsMax" placeholder="100" />
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <label class="input focus-within:outline-none bg-transparent focus-within:border-base-300 w-full md:w-56">
                <input wire:model.live.debounce.300ms="search" class="bg-transparent focus:outline-none rounded-xl w-full" type="search" placeholder="Search" />
            </label>
            {{-- Export: beside search on desktop --}}
            <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'export-emails' }))" class="hidden md:inline-flex btn clr-bg-accent text-base-100 rounded-xl px-4 py-2 shrink-0">Export</button>
            {{-- Delete All: only when items selected --}}
            <button type="button" x-show="$wire.selectedIds.length > 0" x-cloak
                    @click="confirmBulk = true"
                    class="hidden md:inline-flex btn clr-bg-accent text-base-100 rounded-xl px-4 py-2 shrink-0 gap-2">
                Delete
            </button>
        </div>

        {{-- Mobile only: Export + Draft (row 2), Delete All (row 3 when selected), Compose (last) --}}
        <div class="flex flex-col gap-2 md:hidden">
            <div class="flex gap-2">
                <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'export-emails' }))" class="btn clr-bg-accent text-base-100 rounded-xl px-4 py-2 flex-1">Export</button>
                <a href="{{ route('draft') }}" class="btn clr-bg-accent text-base-100 rounded-xl px-4 py-2 flex-1 text-center">Draft</a>
            </div>
            <button type="button" x-show="$wire.selectedIds.length > 0" x-cloak
                    @click="confirmBulk = true"
                    class="btn clr-bg-accent text-base-100 rounded-xl px-4 py-2 w-full gap-2">
                Delete (<span x-text="$wire.selectedIds.length"></span>) Selected
            </button>
            <a href="{{ route('compose') }}" class="btn clr-bg-accent text-base-100 rounded-xl px-4 py-2 w-full text-center">+ Compose Email</a>
        </div>

        {{-- Desktop only: Draft + Compose (right side) --}}
        <div class="hidden md:flex gap-2 items-center shrink-0">
            <a href="{{ route('draft') }}" class="btn clr-bg-accent text-base-100 rounded-xl px-4 py-2 hover-clr-bg-accent-light">Draft</a>
            <a href="{{ route('compose') }}" class="btn clr-bg-accent text-base-100 rounded-xl px-4 py-2 hover-clr-bg-accent-light">+ Compose Email</a>
        </div>
    </div>

    <div class="w-full bg-white overflow-hidden shadow-sm sm:rounded-lg flex-1 min-h-0 flex flex-col">
        {{-- Mobile: Card layout --}}
        <div class="md:hidden divide-y divide-base-200 overflow-auto flex-1 min-h-0">
            @forelse ($this->emails as $email)
                <div x-data="{ expanded: false }" class="border-b border-base-200 last:border-b-0">
                    <div class="flex items-center gap-3 py-3 px-4">
                        <input type="checkbox" class="checkbox checkbox-sm focus:ring-0 shrink-0"
                               wire:model.live="selectedIds" value="{{ $email->id }}" onclick="event.stopPropagation()" />
                        <a href="{{ route('recepients.show', $email->id) }}" class="flex-1 min-w-0 clr-text-primary hover:underline font-medium truncate">{{ $email->subject }}</a>
                        <button type="button" @click="expanded = !expanded" class="btn btn-ghost btn-sm btn-circle shrink-0">
                            <svg x-show="!expanded" class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
                            <svg x-show="expanded" x-cloak class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 15l7-7 7 7"/></svg>
                        </button>
                    </div>
                    <div x-show="expanded" x-transition class="px-4 pb-4 pt-0">
                        <div class="space-y-2 text-sm">
                            <p><span class="text-gray-500 font-medium">Recipients:</span> {{ $email->recipients_sent_count ?? 0 }}/{{ $email->recipients_count }} recipient{{ $email->recipients_count !== 1 ? 's' : '' }}</p>
                            <p><span class="text-gray-500 font-medium">Status:</span> <span class="badge badge-sm {{ $email->status === 'sent' ? 'badge-success' : 'badge-error' }}">{{ ucfirst($email->status) }}</span></p>
                            <p><span class="text-gray-500 font-medium">Date:</span> {{ $email->created_at->timezone('Asia/Manila')->format('M d, Y h:i A') }}</p>
                            <div class="flex gap-2 mt-2">
                                <a href="{{ route('recepients.show', $email->id) }}" class="btn btn-sm clr-bg-accent text-base-100 flex-1">View</a>
                                <button type="button" @click="confirmId = {{ $email->id }}" class="btn btn-sm btn-error text-white flex-1 gap-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6"/></svg>
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-12 text-center text-gray-400 italic">{{ $search ? 'No emails found.' : 'No emails sent yet.' }}</div>
            @endforelse
        </div>

        {{-- Desktop: Table layout --}}
        <div class="hidden md:block overflow-x-auto flex-1 min-h-0">
            <table class="table w-full">
                <thead class="sticky top-0 bg-base-100 z-10">
                <tr>
                    <th>
                        <input type="checkbox" class="checkbox checkbox-sm focus:ring-0" wire:model.live="selectAll" />
                    </th>
                    <th class="font-bold">Subject</th>
                    <th class="font-bold">Recipients</th>
                    <th class="font-bold">Status</th>
                    <th class="font-bold">Date and Time</th>
                    <th class="font-bold">Action</th>
                </tr>
                </thead>
                <tbody>
                    @forelse ($this->emails as $email)
                        <tr class="hover:bg-base-200 group">
                            <th class="opacity-0 group-hover:opacity-100 [&:has(:checked)]:opacity-100 transition-opacity duration-150">
                                <input type="checkbox" class="checkbox checkbox-sm focus:ring-0"
                                       wire:model.live="selectedIds" value="{{ $email->id }}" onclick="event.stopPropagation()" />
                            </th>
                            <td class="cursor-pointer" onclick="window.location='{{ route('recepients.show', $email->id) }}'">
                                <span>{{ $email->subject }}</span>
                            </td>
                            <td class="cursor-pointer" onclick="window.location='{{ route('recepients.show', $email->id) }}'">
                                {{ $email->recipients_sent_count ?? 0 }}/{{ $email->recipients_count }} recipient{{ $email->recipients_count !== 1 ? 's' : '' }}
                            </td>
                            <td class="cursor-pointer" onclick="window.location='{{ route('recepients.show', $email->id) }}'">
                                <span class="badge {{ $email->status === 'sent' ? 'badge-success' : 'badge-error' }}">
                                    {{ ucfirst($email->status) }}
                                </span>
                            </td>
                            <td class="cursor-pointer" onclick="window.location='{{ route('recepients.show', $email->id) }}'">
                                <span class="text-xs text-gray-400">{{ $email->created_at->timezone('Asia/Manila')->format('M d, Y h:i A') }}</span>
                            </td>
                            <td>
                                <button type="button" @click="confirmId = {{ $email->id }}"
                                        class="btn btn-ghost btn-sm text-error hover:bg-error/10 rounded-lg"
                                        title="Move to Archive">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6"/></svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-gray-400 italic py-6">{{ $search ? 'No emails found.' : 'No emails sent yet.' }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between p-2 gap-2 flex-shrink-0">
            <div class="sm:flex-1">
                @if($this->emails->hasPages())
                    {{ $this->emails->links('livewire::tailwind') }}
                @endif
            </div>
        </div>
    </div>

    <x-modal name="export-emails" maxWidth="sm">
        <div class="p-6">
            <div class="flex justify-end">
                <button type="button" onclick="window.dispatchEvent(new CustomEvent('close-modal', { detail: 'export-emails' }))" class="btn btn-ghost btn-sm mt-4">X</button>
            </div>
            <h3 class="text-lg font-semibold mb-4">Export Emails</h3>
            <p class="text-sm text-gray-600 mb-4">Choose a format to download the email list.</p>

            <form method="GET" target="_blank" class="space-y-4">
                <input type="hidden" name="search" value="{{ $search }}">
                <input type="hidden" name="sortBy" value="{{ $sortBy }}">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <label class="form-control w-full">
                        <span class="label-text text-xs text-gray-500">From</span>
                        <input type="date" name="from" class="input input-sm input-bordered w-full" />
                    </label>
                    <label class="form-control w-full">
                        <span class="label-text text-xs text-gray-500">To</span>
                        <input type="date" name="to" class="input input-sm input-bordered w-full" />
                    </label>
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                            formaction="{{ route('dashboard.export.excel') }}"
                            onclick="window.dispatchEvent(new CustomEvent('close-modal', { detail: 'export-emails' }))"
                            class="btn clr-bg-accent text-base-100 flex-1">
                        Export to Excel
                    </button>
                    <button type="submit"
                            formaction="{{ route('dashboard.export.pdf') }}"
                            onclick="window.dispatchEvent(new CustomEvent('close-modal', { detail: 'export-emails' }))"
                            class="btn clr-bg-accent text-base-100 btn-outline flex-1">
                        Export to PDF
                    </button>
                </div>
            </form>
        </div>
    </x-modal>
</div>
