<div class="flex flex-col min-h-0 flex-1" x-data="{ confirmId: null, confirmBulk: false }">
    {{-- Confirm single restore modal --}}
    <div x-show="confirmId !== null" x-cloak
         class="fixed inset-0 z-[100] flex items-center justify-center bg-black/40 backdrop-blur-sm"
         @keydown.escape.window="confirmId = null">
        <div class="bg-base-100 rounded-2xl shadow-xl p-6 w-80 max-w-[90vw] space-y-4">
            <h3 class="text-lg font-semibold">Restore Email?</h3>
            <p class="text-sm text-gray-500">This email will be restored back to your Inbox.</p>
            <div class="flex gap-3 justify-end">
                <button type="button" @click="confirmId = null" class="btn btn-ghost btn-sm">Cancel</button>
                <button type="button" @click="$wire.restoreEmail(confirmId); confirmId = null" class="btn btn-success btn-sm text-base-100 p-2">Restore</button>
            </div>
        </div>
    </div>

    {{-- Confirm bulk restore modal --}}
    <div x-show="confirmBulk" x-cloak
         class="fixed inset-0 z-[100] flex items-center justify-center bg-black/40 backdrop-blur-sm"
         @keydown.escape.window="confirmBulk = false">
        <div class="bg-base-100 rounded-2xl shadow-xl p-6 w-80 max-w-[90vw] space-y-4">
            <h3 class="text-lg font-semibold">Restore Selected?</h3>
            <p class="text-sm text-gray-500">All selected emails will be restored back to your Inbox.</p>
            <div class="flex gap-3 justify-end">
                <button type="button" @click="confirmBulk = false" class="btn btn-ghost btn-sm">Cancel</button>
                <button type="button" @click="$wire.restoreSelected(); confirmBulk = false" class="btn btn-success btn-sm text-base-100 p-2">Restore</button>
            </div>
        </div>
    </div>

    {{-- Top bar --}}
    <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between p-2 flex-shrink-0">
        <div class="flex items-center gap-2 w-full md:w-auto">
            <x-email-filters buttonLabel="Filter" title="Archive Filters" />
            <label class="input focus-within:outline-none bg-transparent focus-within:border-base-300 w-full md:w-56">
                <input wire:model.live.debounce.300ms="search" class="bg-transparent focus:outline-none rounded-xl w-full" type="search" placeholder="Search archive..." />
            </label>
            {{-- Restore All: only when items selected --}}
            <button type="button" x-show="$wire.selectedIds.length > 0" x-cloak
                    @click="confirmBulk = true"
                    class="hidden md:inline-flex btn btn-success text-white rounded-xl px-4 py-2 shrink-0 gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                Restore (<span x-text="$wire.selectedIds.length"></span>)
            </button>
        </div>
        <div class="flex flex-col gap-2 md:hidden">
            <button type="button" x-show="$wire.selectedIds.length > 0" x-cloak
                    @click="confirmBulk = true"
                    class="btn btn-success text-white rounded-xl px-4 py-2 w-full gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                Restore (<span x-text="$wire.selectedIds.length"></span>) Selected
            </button>
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
                        <span class="flex-1 min-w-0 font-medium truncate text-gray-600">{{ $email->subject }}</span>
                        <button type="button" @click="expanded = !expanded" class="btn btn-ghost btn-sm btn-circle shrink-0">
                            <svg x-show="!expanded" class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
                            <svg x-show="expanded" x-cloak class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 15l7-7 7 7"/></svg>
                        </button>
                    </div>
                    <div x-show="expanded" x-transition class="px-4 pb-4 pt-0">
                        <div class="space-y-2 text-sm">
                            <p><span class="text-gray-500 font-medium">Recipients:</span> {{ $email->recipients_sent_count ?? 0 }}/{{ $email->recipients_count }} recipient{{ $email->recipients_count !== 1 ? 's' : '' }}</p>
                            <p><span class="text-gray-500 font-medium">Status:</span> <span class="badge badge-sm {{ $email->status === 'sent' ? 'badge-success' : 'badge-error' }}">{{ ucfirst($email->status) }}</span></p>
                            <p><span class="text-gray-500 font-medium">Archived:</span> {{ $email->deleted_at->timezone('Asia/Manila')->format('M d, Y h:i A') }}</p>
                            <button type="button" @click="confirmId = {{ $email->id }}" class="btn btn-sm btn-success text-white mt-2 w-full gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                                Restore
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-12 text-center text-gray-400 italic">{{ $search ? 'No archived emails found.' : 'Archive is empty.' }}</div>
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
                    <th class="font-bold">Archived On</th>
                    <th class="font-bold">Action</th>
                </tr>
                </thead>
                <tbody>
                    @forelse ($this->emails as $email)
                        <tr class="hover:bg-base-200 group">
                            <th class="opacity-0 group-hover:opacity-100 [&:has(:checked)]:opacity-100 transition-opacity duration-150">
                                <input type="checkbox" class="checkbox checkbox-sm focus:ring-0"
                                       wire:model.live="selectedIds" value="{{ $email->id }}" />
                            </th>
                            <td><span class="text-gray-600">{{ $email->subject }}</span></td>
                            <td>{{ $email->recipients_sent_count ?? 0 }}/{{ $email->recipients_count }} recipient{{ $email->recipients_count !== 1 ? 's' : '' }}</td>
                            <td>
                                <span class="badge {{ $email->status === 'sent' ? 'badge-success' : 'badge-error' }}">
                                    {{ ucfirst($email->status) }}
                                </span>
                            </td>
                            <td>
                                <span class="text-xs text-gray-400">{{ $email->deleted_at->timezone('Asia/Manila')->format('M d, Y h:i A') }}</span>
                            </td>
                            <td>
                                <button type="button" @click="confirmId = {{ $email->id }}"
                                        class="btn btn-ghost btn-sm text-success hover:bg-success/10 rounded-lg gap-1"
                                        title="Restore to Inbox">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                                    Restore
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-gray-400 italic py-6">{{ $search ? 'No archived emails found.' : 'Archive is empty.' }}</td>
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
</div>
