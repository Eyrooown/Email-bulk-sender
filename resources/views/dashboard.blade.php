<x-app-layout>
    <x-slot name="header">
    </x-slot>
        <div class="w-full mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center justify-between p-2">
                        <div class="flex items-center gap-4 py-4">
                            <div class="dropdown w-1/2 dropdown-end">
                            <div tabindex="0" role="button" class="btn border-2 border-black rounded-xl m-1 w-full hover-clr-bg-accent"><x-icons.sort class="w-4 h-4 inline-block" /> Sort By:</div>
                            <ul tabindex="-1" class="dropdown-content menu bg-base-100 rounded-box z-1 w-full p-2 shadow-sm">
                                <li><a>Item 1</a></li>
                                <li><a>Item 2</a></li>
                            </ul>
                            </div>
                            <label class="input focus-within:outline-none bg-transparent focus-within:border-base-300">
                                <input class="bg-transparent focus:outline-none rounded-xl" type="search" required placeholder="Search" />
                            </label>
                        </div>

                        <div>
                            <button class="btn clr-bg-accent text-base-100 rounded-xl p-4 hover-clr-bg-accent-light">+ Compose Email</button>
                        </div>
                    </div>
            <div class="w-full bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div x-data="{ selectAll: false }" class="overflow-auto" style="max-height: 65vh;">
                    <table class="table">
                        <!-- head -->
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
                            @forelse ($emails as $email)
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
                                    <td colspan="7" class="text-center text-gray-400 italic py-6">No emails sent yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="flex items-center justify-end p-2">
                        <Button class="clr-bg-accent text-base-100 rounded-xl w-24 p-2">Export</Button>
                    </div>
            </div>
        </div>
    </div>
</x-app-layout>
