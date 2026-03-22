<x-app-layout>
    <div class="flex items-end justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold clr-txt-primary mb-1">
                My Proposals
            </h1>
            <p class="text-gray-400 text-sm">{{ $proposals->count() }} {{ str('proposal')->plural($proposals->count()) }}</p>
        </div>

        <form method="POST" action="{{ route('proposal.store') }}">
            @csrf
            <button type="submit"
                class="inline-flex items-center gap-2 bg-indigo-500 hover:bg-indigo-400 text-white font-semibold text-sm px-5 py-2.5 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                New Proposal
            </button>
        </form>
    </div>

    @if (session('status'))
        <div class="mb-4 rounded-lg bg-emerald-500/10 border border-emerald-500/30 p-3 text-sm text-emerald-300">
            {{ session('status') }}
        </div>
    @endif

    @if ($proposals->isEmpty())
        <div class="flex flex-col items-center justify-center py-24 text-center">
            <p class="text-gray-400 text-sm">No proposals yet. Create your first one!</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($proposals as $proposal)
                <div class="group relative bg-gray-900 border border-gray-800 hover:border-gray-600 rounded-xl overflow-hidden transition-all">
                    <a href="{{ route('proposal.edit', $proposal) }}" class="block">
                        <div class="aspect-video w-full flex items-center justify-center
                            @switch($proposal->theme)
                                @case('midnight') bg-gradient-to-br from-gray-900 to-indigo-950 @break
                                @case('aurora') bg-gradient-to-br from-purple-950 to-teal-950 @break
                                @case('slate') bg-gradient-to-br from-slate-800 to-slate-950 @break
                                @case('rose') bg-gradient-to-br from-rose-950 to-gray-900 @break
                                @case('forest') bg-gradient-to-br from-emerald-950 to-gray-900 @break
                                @default bg-gradient-to-br from-gray-900 to-indigo-950
                            @endswitch">
                            <span class="text-white font-semibold text-sm opacity-60 px-4 text-center line-clamp-2">
                                {{ $proposal->title }}
                            </span>
                        </div>
                    </a>

                    <div class="p-4">
                        <a href="{{ route('proposal.edit', $proposal) }}"
                            class="text-white font-medium text-sm hover:text-indigo-300 transition line-clamp-1">
                            {{ $proposal->title }}
                        </a>
                        <div class="flex items-center justify-between mt-1">
                            <p class="text-xs text-gray-500">
                                {{ $proposal->slides_count }} {{ str('slide')->plural($proposal->slides_count) }}
                                · {{ $proposal->updated_at->diffForHumans() }}
                            </p>

                            <div class="flex items-center gap-2">
                                <a href="{{ route('proposal.export.pdf', $proposal) }}"
                                    class="text-xs text-indigo-300 hover:text-indigo-200 transition">
                                    PDF
                                </a>
                                <form method="POST" action="{{ route('proposal.destroy', $proposal) }}"
                                    onsubmit="return confirm('Delete this proposal?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-gray-600 hover:text-rose-400 transition" title="Delete">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-app-layout>
