<div class="flex flex-col min-h-0 flex-1">
    {{-- Top bar --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between p-2 flex-shrink-0">
        <div class="flex items-center gap-2 py-2 w-full sm:w-auto flex-wrap">
            <div class="dropdown dropdown-bottom">
                <button tabindex="0" type="button" class="btn w-full sm:w-36 border-2 border-black rounded-xl hover-clr-bg-accent">
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
                            <p class="text-xs text-gray-400 mt-2">Filtered using Philippines time (Asia/Manila).</p>
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
            <label class="input focus-within:outline-none bg-transparent focus-within:border-base-300 flex-1 min-w-0">
                <input wire:model.live.debounce.300ms="search" class="bg-transparent focus:outline-none rounded-xl w-full" type="search" placeholder="Search " />
            </label>
            <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'export-emails' }))" class="btn clr-bg-accent text-base-100 rounded-xl px-4 py-2 min-w-24 w-full sm:w-auto">Export</button>
        </div>

        <div class="w-full sm:w-auto flex flex-wrap gap-2 justify-end">
            <a href="{{ route('draft') }}" class="btn btn-outline border-2 border-base-300 text-base-content text-lg rounded-xl px-4 py-3 hover-clr-bg-accent hover:text-base-100 w-full sm:w-auto text-center">Draft</a>
            <a href="{{ route('compose') }}" class="btn clr-bg-accent text-base-100 text-lg rounded-xl px-4 py-3 hover-clr-bg-accent-light w-full sm:w-auto text-center">+ Compose Email</a>
        </div>
    </div>

    <div class="w-full bg-white overflow-hidden shadow-sm sm:rounded-lg flex-1 min-h-0 flex flex-col">
        {{-- Mobile: Card layout --}}
        <div class="md:hidden divide-y divide-base-200 overflow-auto flex-1 min-h-0">
            @forelse ($this->emails as $email)
                <div x-data="{ expanded: false }" class="border-b border-base-200 last:border-b-0">
                    <div class="flex items-center justify-between gap-3 py-3 px-4">
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
                            <a href="{{ route('recepients.show', $email->id) }}" class="btn btn-sm clr-bg-accent text-base-100 mt-2">View</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-12 text-center text-gray-400 italic">{{ $search ? 'No emails found.' : 'No emails sent yet.' }}</div>
            @endforelse
        </div>

        {{-- Desktop: Table layout --}}
        <div x-data="{ selectAll: false }" class="hidden md:block overflow-x-auto flex-1 min-h-0">
            <table class="table w-full">
                <thead class="sticky top-0 bg-base-100 z-10">
                <tr>
                    <th>
                        <label>
                            <input type="checkbox" class="focus-within:ring-0" x-model="selectAll" />
                        </label>
                    </th>
                    <th class="font-bold">Subject</th>
                    <th class="font-bold">Recipients</th>
                    <th class="font-bold">Status</th>
                    <th></th>
                    <th class="font-bold">Date</th>
                </tr>
                </thead>
                <tbody>
                    @forelse ($this->emails as $email)
                        <tr class="cursor-pointer hover:bg-base-200" onclick="window.location='{{ route('recepients.show', $email->id) }}'">
                            <th>
                                <label>
                                    <input type="checkbox" class="focus-within:ring-0" :checked="selectAll" onclick="event.stopPropagation()" />
                                </label>
                            </th>
                            <td>
                                <span>{{ $email->subject }}</span>
                            </td>
                            <td>{{ $email->recipients_sent_count ?? 0 }}/{{ $email->recipients_count }} recipient{{ $email->recipients_count !== 1 ? 's' : '' }}</td>
                            <th>
                                <span class="badge {{ $email->status === 'sent' ? 'badge-success' : 'badge-error' }}">
                                    {{ ucfirst($email->status) }}
                                </span>
                            </th>
                            <th></th>
                            <th>
                                <span class="text-xs text-gray-400">{{ $email->created_at->timezone('Asia/Manila')->format('M d, Y h:i A') }}</span>
                            </th>
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
