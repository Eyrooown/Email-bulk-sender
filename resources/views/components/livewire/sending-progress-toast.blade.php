<div wire:poll.500ms="checkProgress">
    @if($show)
        <div class="fixed bottom-8 left-1/2 -translate-x-1/2 z-[9999] bg-gray-900 text-white px-6 py-4 rounded-2xl shadow-2xl w-full max-w-sm">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-4 h-4 border-2 border-gray-500 border-t-white rounded-full animate-spin shrink-0"></div>
                <strong class="text-sm">Sending your email...</strong>
                <span class="ml-auto text-xs text-gray-400">{{ $current }}/{{ $total }}</span>
            </div>
            <div class="w-full bg-gray-700 rounded-full h-1.5">
                <div class="bg-red-500 h-1.5 rounded-full transition-all duration-300"
                     style="width: {{ $total > 0 ? ($current / $total) * 100 : 0 }}%"></div>
            </div>
        </div>
    @endif
</div>
