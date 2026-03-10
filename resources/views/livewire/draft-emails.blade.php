<div>
    <div class="mb-4">
        <label class="input focus-within:outline-none bg-transparent focus-within:border-base-300 w-80 max-w-full">
            <input wire:model.live.debounce.300ms="search" class="bg-transparent focus:outline-none rounded-xl" type="search" placeholder="Search drafts..." />
        </label>
    </div>
    <div class="w-full bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="overflow-auto" style="height: 63vh;">
            <table class="table">
                <thead class="sticky top-0 bg-base-100 z-10">
                <tr>
                    <th class="font-bold">Subject</th>
                    <th class="font-bold">Recipients</th>
                    <th class="font-bold">Date</th>
                </tr>
                </thead>
                <tbody>
                    @forelse ($this->emails as $email)
                        <tr class="cursor-pointer hover:bg-base-200" onclick="window.location='{{ route('compose') }}?draft={{ $email->id }}'">
                            <td>
                                <span>{{ $email->subject ?: '(No subject)' }}</span>
                            </td>
                            <td>{{ $email->recipients_count }} recipient{{ $email->recipients_count !== 1 ? 's' : '' }}</td>
                            <td>
                                <span class="text-xs text-gray-400">{{ $email->updated_at->format('M d, Y H:i') }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-gray-400 italic py-12">{{ $search ? 'No drafts found.' : 'No drafts yet.' }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($this->emails->hasPages())
            <div class="p-2 border-t border-base-200">
                {{ $this->emails->links('livewire::tailwind') }}
            </div>
        @endif
    </div>
</div>
