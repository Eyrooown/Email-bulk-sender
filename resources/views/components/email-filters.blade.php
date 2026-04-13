@props([
    'buttonLabel' => 'Filter',
    'title' => 'Filters',
    'resetAction' => 'resetFilters',
])

<div class="dropdown dropdown-bottom shrink-0">
    <button tabindex="0" type="button" class="btn md:w-36 border-2 border-black rounded-xl hover-clr-bg-accent">
        <x-icons.actions.sort classes="w-4 h-4 inline-block" /> {{ $buttonLabel }}
    </button>
    <div tabindex="-1" class="dropdown-content z-50 mt-2 w-[22rem] max-w-[90vw] rounded-2xl bg-base-100 p-4 shadow-xl border border-base-200">
        <div class="flex items-center justify-between mb-3">
            <div class="font-semibold">{{ $title }}</div>
            <button type="button" wire:click="{{ $resetAction }}" class="btn btn-ghost btn-sm">Reset</button>
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

