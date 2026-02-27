<div class="flex flex-col min-h-0 flex-1">
    <div class="flex items-center justify-between p-2 flex-shrink-0">
        <div class="flex justify-center items-center py-4">
            <div class="dropdown dropdown-end">
                <button tabindex="0" class="btn w-48 border-2 border-black rounded-xl m-1 hover-clr-bg-accent"><x-icons.sort class="w-4 h-4 inline-block" /> Sort By:</button>
                <ul tabindex="-1" class="dropdown-content menu bg-base-100 rounded-box z-50 w-56 p-2 shadow-lg mt-1">
                    <li><a href="#" wire:click.prevent="$set('sortBy', 'subject_asc')" class="{{ $sortBy === 'subject_asc' ? 'active' : '' }}">Alphabetical (A → Z)</a></li>
                    <li><a href="#" wire:click.prevent="$set('sortBy', 'subject_desc')" class="{{ $sortBy === 'subject_desc' ? 'active' : '' }}">Alphabetical (Z → A)</a></li>
                    <li><a href="#" wire:click.prevent="$set('sortBy', 'date_desc')" class="{{ $sortBy === 'date_desc' ? 'active' : '' }}">Date (Newest first)</a></li>
                    <li><a href="#" wire:click.prevent="$set('sortBy', 'date_asc')" class="{{ $sortBy === 'date_asc' ? 'active' : '' }}">Date (Oldest first)</a></li>
                </ul>
            </div>
            <label class="input focus-within:outline-none bg-transparent focus-within:border-base-300 flex-1">
                <input wire:model.live.debounce.300ms="search" class="bg-transparent focus:outline-none rounded-xl" type="search" placeholder="Search" />
            </label>
        </div>

        <div>
            <a href="{{ route('compose') }}" class="btn clr-bg-accent text-base-100 rounded-xl p-4 hover-clr-bg-accent-light">+ Compose Email</a>
        </div>
    </div>
    <div class="w-full bg-white overflow-hidden shadow-sm sm:rounded-lg flex-1 min-h-0 flex flex-col">
        <div x-data="{ selectAll: false }" class="overflow-auto flex-1 min-h-0">
            <table class="table">
                <thead class="sticky top-0 bg-base-100 z-10">
                <tr>
                    <th>
                        <label>
                            <input type="checkbox" class="focus-within:ring-0" x-model="selectAll" />
                        </label>
                    </th>
                    <th class="font-bold">Subject</th>
                    <th class="font-bold">Recepients</th>
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
                            <td>{{ $email->recipients_count }} recipient{{ $email->recipients_count !== 1 ? 's' : '' }}</td>
                            <th>
                                <span class="badge {{ $email->status === 'sent' ? 'badge-success' : 'badge-error' }}">
                                    {{ ucfirst($email->status) }}
                                </span>
                            </th>
                            <th></th>
                            <th>
                                <span class="text-xs text-gray-400">{{ $email->created_at->format('M d, Y') }}</span>
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
        <div class="flex items-center justify-between p-2 flex-wrap gap-2 flex-shrink-0">
            <div class="flex-1">
                @if($this->emails->hasPages())
                    {{ $this->emails->links('livewire::tailwind') }}
                @endif
            </div>
            <button class="clr-bg-accent text-base-100 rounded-xl w-24 p-2">Export</button>
        </div>
    </div>
</div>
