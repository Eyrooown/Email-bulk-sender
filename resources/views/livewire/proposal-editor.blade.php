<div class="flex h-screen overflow-hidden bg-gray-950" wire:poll.5000ms="saveAll">
    <div class="fixed top-0 left-0 right-0 z-50 h-12 bg-gray-900 border-b border-gray-800 flex items-center px-4 gap-3">
        <a href="{{ route('proposal') }}"
            class="flex items-center gap-1.5 text-gray-400 hover:text-white transition mr-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </svg>
            <span
                class="w-5 h-5 rounded bg-indigo-500 flex items-center justify-center text-xs font-bold text-white">P</span>
        </a>

        <input type="text" wire:model.blur="title" wire:change="updateTitle"
            class="bg-transparent text-white text-sm font-medium w-56 border-0 border-b border-transparent hover:border-gray-600 focus:border-indigo-400 focus:outline-none py-0.5 transition"
            placeholder="Proposal title" />

        <span class="flex-1"></span>

        <div class="flex items-center gap-2 text-xs text-gray-400">
            <span>Theme</span>
            <select wire:model.live="theme" wire:change="updateTheme"
                class="bg-gray-800 border border-gray-700 text-white text-xs rounded px-2 py-1 focus:outline-none focus:border-indigo-400">
                <option value="midnight">Midnight</option>
                <option value="aurora">Aurora</option>
                <option value="slate">Slate</option>
                <option value="rose">Rose</option>
                <option value="forest">Forest</option>
            </select>
        </div>

        <button wire:click="toggleViewMode"
            class="inline-flex items-center gap-1.5 {{ $viewMode === 'print' ? 'bg-amber-500 hover:bg-amber-400' : 'bg-emerald-500 hover:bg-emerald-400' }} text-white text-xs font-semibold px-3 py-1.5 rounded transition-colors">
            {{ $viewMode === 'responsive' ? 'Print View' : 'Responsive' }}
        </button>

        <a href="{{ route('proposal.preview', $proposal) }}" target="_blank"
            class="inline-flex items-center gap-1.5 bg-gray-600 hover:bg-gray-500 text-white text-xs font-semibold px-3 py-1.5 rounded transition-colors">
            Preview
        </a>

        <a href="{{ route('proposal.export.pdf', $proposal) }}" target="_blank"
            class="inline-flex items-center gap-1.5 bg-indigo-500 hover:bg-indigo-400 text-white text-xs font-semibold px-3 py-1.5 rounded transition-colors">
            Export PDF
        </a>

        <div id="toast"
            class="fixed bottom-6 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-4 py-2 rounded-full shadow-lg opacity-0 translate-y-2 transition-all duration-300 pointer-events-none z-[100]">
            Saved
        </div>
    </div>

    <aside
        class="fixed left-0 top-12 bottom-0 w-[200px] bg-gray-900 border-r border-gray-800 overflow-y-auto z-40 flex flex-col">
        <div class="p-2 space-y-1.5">
            @foreach ($proposal->slides as $i => $slide)
                @php
                    $thumbContentOverride = $i === $activeSlideIndex ? $this->activeSlidePreviewContent() : null;
                @endphp
                <div wire:key="proposal-thumb-{{ $slide->id }}"
                    class="group relative w-full rounded-lg overflow-hidden border-2 transition-all {{ $activeSlideIndex === $i ? 'border-indigo-500 shadow-lg shadow-indigo-900/30' : 'border-transparent hover:border-gray-600' }}">
                    <button type="button" wire:click="selectSlide({{ $i }})"
                        class="block w-full cursor-pointer border-0 bg-transparent p-0 text-left focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 focus-visible:ring-offset-gray-900 rounded-lg"
                        title="Slide {{ $i + 1 }}">
                        {{-- Same markup as main canvas (max-w-4xl = 56rem), scaled to sidebar width via container queries --}}
                        <div
                            class="relative w-full aspect-[1.414/1] overflow-hidden text-left pointer-events-none [container-type:inline-size]">
                            <div
                                class="absolute left-0 top-0 h-[calc(56rem/1.414)] w-[56rem] origin-top-left will-change-transform [transform:scale(calc(100cqw/56rem))]">
                                <div class="relative h-full w-full overflow-hidden rounded-lg shadow-md shadow-black/40">
                                    <div
                                        class="absolute inset-0 overflow-y-auto rounded-lg {{ $this->themeClass() }}">
                                        @include('livewire.partials.proposal-slide-content', [
                                            'slide' => $slide,
                                            'mini' => false,
                                            'contentOverride' => $thumbContentOverride,
                                            'printMode' => false,
                                        ])
                                    </div>
                                </div>
                            </div>
                        </div>
                    </button>

                    <span
                        class="pointer-events-none absolute bottom-1 right-1.5 z-10 text-[8px] font-mono text-white/40">{{ $i + 1 }}</span>

                    @if ($proposal->slides->count() > 1)
                        <button type="button" wire:click.stop="deleteSlide({{ $i }})"
                            class="absolute right-1 top-1 z-20 flex h-6 w-6 items-center justify-center rounded-md bg-gray-950/90 text-xs font-semibold text-gray-300 shadow-sm ring-1 ring-white/10 hover:bg-rose-600/90 hover:text-white hover:ring-rose-500/50"
                            title="Delete slide {{ $i + 1 }}"
                            aria-label="Delete slide {{ $i + 1 }}">
                            ×
                        </button>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="p-2 mt-auto border-t border-gray-800 relative">
            <button type="button" wire:click="$toggle('addSlideMenuOpen')"
                class="w-full flex items-center justify-center gap-1.5 text-xs text-gray-400 hover:text-white bg-gray-800 hover:bg-gray-700 rounded-lg py-2 transition">
                Add Slide
            </button>

            @if ($addSlideMenuOpen)
                <div wire:click.outside="$set('addSlideMenuOpen', false)"
                    class="absolute bottom-full left-0 right-0 mb-1 bg-gray-800 border border-gray-700 rounded-lg overflow-hidden shadow-xl z-50">
                    @foreach ([
        'fixed-cover' => 'Fixed Cover',
        'fixed-executive' => 'Fixed Executive Summary',
        'fixed-whois' => 'Fixed Who is Odecci',
        'fixed-strategy-cards' => 'Fixed Strategy Cards',
        'fixed-problem-statement' => 'Fixed Problem Statement',
        'fixed-custom-solution' => 'Fixed Custom Solution',
        'fixed-scope-basic' => 'Fixed Scope Basic',
        'fixed-scope-business' => 'Fixed Scope Business',
        'fixed-scope-store' => 'Fixed Scope Store',
        'fixed-terms' => 'Fixed Terms & Condition',
        'fixed-projects' => 'Fixed Projects',
        'fixed-organizations' => 'Fixed Organizations',
        'fixed-testimonial' => 'Fixed Testimonial',
        'fixed-why-customize' => 'Fixed Why Customize',
        'fixed-guidance' => 'Fixed Guidance',
        'fixed-contact' => 'Fixed Contact',
    ] as $layout => $label)
                        <button type="button" wire:click="addSlide('{{ $layout }}')"
                            class="w-full text-left px-3 py-2 text-xs text-gray-300 hover:bg-gray-700 hover:text-white transition">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            @endif
        </div>
    </aside>

    <main class="flex-1 ml-[200px] mr-[290px] mt-12 flex items-center justify-center overflow-hidden bg-gray-950 p-8">
        @if ($currentSlide = $proposal->slides->get($activeSlideIndex))
            @php $previewContent = $this->activeSlidePreviewContent(); @endphp
            <div class="w-full max-w-4xl {{ $viewMode === 'print' ? 'max-w-[1122px]' : '' }}">
                @if ($viewMode === 'print')
                    <div class="flex flex-col gap-4">
                        @foreach ($proposal->slides as $index => $slide)
                            <div class="relative w-full" style="aspect-ratio: 1.414 / 1;">
                                <div
                                    class="absolute inset-0 rounded-xl overflow-hidden shadow-2xl shadow-black/60 {{ $this->themeClass() }}">
                                    @include('livewire.partials.proposal-slide-content', [
                                        'slide' => $slide,
                                        'mini' => false,
                                        'contentOverride' => null,
                                        'printMode' => true,
                                    ])
                                </div>
                            </div>
                            @if ($index < $proposal->slides->count() - 1)
                                <div class="text-center text-xs text-gray-500 py-2">--- Page {{ $index + 1 }} ---
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="relative w-full" style="aspect-ratio: 1.414 / 1;">
                        <div
                            class="absolute inset-0 rounded-xl overflow-y-auto shadow-2xl shadow-black/60 {{ $this->themeClass() }}">
                            @include('livewire.partials.proposal-slide-content', [
                                'slide' => $currentSlide,
                                'mini' => false,
                                'contentOverride' => $previewContent,
                                'printMode' => false,
                            ])
                        </div>
                    </div>
                @endif
                <div class="text-center mt-3 text-xs text-gray-600 font-mono">
                    {{ $activeSlideIndex + 1 }} / {{ $proposal->slides->count() }}
                </div>
            </div>
        @endif
    </main>

    <aside class="fixed right-0 top-12 bottom-0 w-[290px] bg-gray-900 border-l border-gray-800 overflow-y-auto z-40">
        <div class="p-4 space-y-5">
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-widest">Edit Content</div>

            @if ($currentSlide = $proposal->slides->get($activeSlideIndex))
                @php $layout = $currentSlide->layout; @endphp

                @if (in_array($layout, ['title', 'content', 'two-col']))
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">
                            {{ $layout === 'title' ? 'Title' : 'Heading' }}
                        </label>
                        <textarea wire:model.live.debounce.400ms="heading" rows="2"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 resize-none transition placeholder-gray-600"></textarea>
                    </div>
                @endif

                @if ($layout === 'title')
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Subtitle</label>
                        <textarea wire:model.live.debounce.400ms="subheading" rows="2"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 resize-none transition placeholder-gray-600"></textarea>
                    </div>
                @endif

                @if ($layout === 'content')
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Body Text</label>
                        <textarea wire:model.live.debounce.400ms="body" rows="7"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 resize-none transition placeholder-gray-600"></textarea>
                    </div>
                @endif

                @if ($layout === 'two-col')
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Left Column</label>
                        <textarea wire:model.live.debounce.400ms="col1" rows="5"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 resize-none transition placeholder-gray-600"></textarea>
                    </div>
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Right Column</label>
                        <textarea wire:model.live.debounce.400ms="col2" rows="5"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 resize-none transition placeholder-gray-600"></textarea>
                    </div>
                @endif

                @if ($layout === 'quote')
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Quote</label>
                        <textarea wire:model.live.debounce.400ms="quote" rows="4"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 resize-none transition placeholder-gray-600"></textarea>
                    </div>
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Attribution</label>
                        <input wire:model.live.debounce.400ms="author" type="text"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                    </div>
                @endif
            @endif

            @if ($layout === 'fixed-cover')
                <div class="space-y-3">
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Tagline</label>
                        <input wire:model.live.debounce.400ms="tagline" type="text"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                    </div>
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Line 1</label>
                        <input wire:model.live.debounce.400ms="line1" type="text"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                    </div>
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Line 2</label>
                        <input wire:model.live.debounce.400ms="line2" type="text"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                    </div>
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Line 3</label>
                        <input wire:model.live.debounce.400ms="line3" type="text"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                    </div>
                </div>
            @endif

            @if ($layout === 'fixed-executive')
                <div>
                    <label class="text-xs text-gray-400 font-medium mb-1.5 block">Heading</label>
                    <textarea wire:model.live.debounce.400ms="heading" rows="2"
                        class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 resize-none transition placeholder-gray-600"></textarea>
                </div>
                <div class="mt-4">
                    <label class="text-xs text-gray-400 font-medium mb-1.5 block">Opening Paragraphs</label>
                    <textarea wire:model.live.debounce.400ms="body" rows="5"
                        class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 resize-none transition placeholder-gray-600"></textarea>
                </div>
                <div class="mt-4 pt-2 border-t border-gray-700">
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-xs text-gray-400 font-semibold">Key Highlights</label>
                        <button type="button" wire:click="addBodyHighlight"
                            class="text-xs text-indigo-400 hover:text-indigo-300">+ Add</button>
                    </div>
                    @forelse($bodyHighlights as $index => $highlight)
                        <div class="flex items-start gap-2 mb-2">
                            <input wire:model.live.debounce.400ms="bodyHighlights.{{ $index }}" type="text"
                                placeholder="**Bold Label:** Description"
                                class="flex-1 bg-gray-800 border border-gray-700 text-white text-xs rounded px-2 py-1.5 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                            <button type="button" wire:click="removeBodyHighlight({{ $index }})"
                                class="text-gray-500 hover:text-rose-400 p-1.5">x</button>
                        </div>
                    @empty
                        <p class="text-xs text-gray-500 italic">No highlights</p>
                    @endforelse
                </div>
                <div class="mt-4 pt-2 border-t border-gray-700">
                    <label class="text-xs text-gray-400 font-medium mb-1.5 block">Closing Paragraph</label>
                    <textarea wire:model.live.debounce.400ms="bodyFooter" rows="3"
                        class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 resize-none transition placeholder-gray-600"></textarea>
                </div>
            @endif

            @if ($layout === 'fixed-whois')
                <div class="space-y-3">
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Top heading</label>
                        <input wire:model.live.debounce.400ms="top_heading" type="text"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                    </div>

                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Main heading</label>
                        <textarea wire:model.live.debounce.400ms="heading" rows="2"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 resize-none transition placeholder-gray-600"></textarea>
                    </div>

                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Body</label>
                        <textarea wire:model.live.debounce.400ms="body" rows="7"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 resize-none transition placeholder-gray-600"></textarea>
                    </div>

                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Website</label>
                        <input wire:model.live.debounce.400ms="website" type="text"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                    </div>

                    <div class="pt-2 border-t border-gray-700">
                        <div class="text-xs text-gray-400 font-semibold mb-2">Bullets (6 items)</div>
                        @foreach (range(0, 5) as $idx)
                            <div class="mt-2">
                                <label class="text-xs text-gray-400 font-medium mb-1.5 block">Bullet
                                    {{ $idx + 1 }}</label>
                                <input wire:model.live.debounce.400ms="bullets.{{ $idx }}" type="text"
                                    class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($layout === 'fixed-strategy-cards')
                <div class="space-y-3">
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Heading</label>
                        <textarea wire:model.live.debounce.400ms="heading" rows="2"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 resize-none transition placeholder-gray-600"></textarea>
                    </div>

                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Subheading</label>
                        <textarea wire:model.live.debounce.400ms="subheading" rows="3"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 resize-none transition placeholder-gray-600"></textarea>
                    </div>

                    @foreach (range(1, 5) as $i)
                        <div class="pt-2 border-t border-gray-700">
                            <label class="text-xs text-gray-400 font-medium mb-1.5 block">Card {{ $i }}
                                Title</label>
                            <textarea wire:model.live.debounce.400ms="cardTitles.{{ $i }}" rows="2"
                                class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 resize-none transition placeholder-gray-600"></textarea>

                            <label class="text-xs text-gray-400 font-medium mb-1.5 block mt-3">Card
                                {{ $i }} Description</label>
                            <textarea wire:model.live.debounce.400ms="cardBodies.{{ $i }}" rows="3"
                                class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 resize-none transition placeholder-gray-600"></textarea>
                        </div>
                    @endforeach
                </div>
            @endif

            @if ($layout === 'fixed-problem-statement')
                <div class="space-y-3">
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Heading</label>
                        <input wire:model.live.debounce.400ms="heading" type="text"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                    </div>
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Body Text</label>
                        <textarea wire:model.live.debounce.400ms="body" rows="4"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 resize-none transition placeholder-gray-600"></textarea>
                    </div>
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Pill Label</label>
                        <input wire:model.live.debounce.400ms="pill" type="text"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                    </div>
                    <div class="pt-2 border-t border-gray-700">
                        <div class="text-xs text-gray-400 font-semibold mb-2">Problems (5 items)</div>
                        @foreach (range(0, 4) as $idx)
                            <div class="mt-2">
                                <label class="text-xs text-gray-400 font-medium mb-1.5 block">Problem
                                    {{ $idx + 1 }}</label>
                                <input wire:model.live.debounce.400ms="problems.{{ $idx }}" type="text"
                                    class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($layout === 'fixed-custom-solution')
                <div class="space-y-3">
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Heading</label>
                        <input wire:model.live.debounce.400ms="heading" type="text"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                    </div>
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Body Text</label>
                        <textarea wire:model.live.debounce.400ms="body" rows="4"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 resize-none transition placeholder-gray-600"></textarea>
                    </div>
                    @foreach (range(1, 5) as $i)
                        <div class="pt-2 border-t border-gray-700">
                            <label class="text-xs text-gray-400 font-medium mb-1.5 block">Solution {{ $i }}
                                Title</label>
                            <input wire:model.live.debounce.400ms="solutionTitles.{{ $i }}" type="text"
                                class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                            <label class="text-xs text-gray-400 font-medium mb-1.5 block mt-2">Solution
                                {{ $i }} Description</label>
                            <textarea wire:model.live.debounce.400ms="solutionDescs.{{ $i }}" rows="2"
                                class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 resize-none transition placeholder-gray-600"></textarea>
                        </div>
                    @endforeach
                </div>
            @endif

            @if (in_array($layout, ['fixed-scope-basic', 'fixed-scope-business', 'fixed-scope-store']))
                <div class="space-y-3">
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Package Name</label>
                        <input wire:model.live.debounce.400ms="packageName" type="text"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                    </div>
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Ideal For</label>
                        <textarea wire:model.live.debounce.400ms="idealFor" rows="3"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 resize-none transition placeholder-gray-600"></textarea>
                    </div>
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Revision</label>
                        <input wire:model.live.debounce.400ms="revision" type="text"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                    </div>
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Benefit Label</label>
                        <input wire:model.live.debounce.400ms="benefit" type="text"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                    </div>

                    <div class="pt-2 border-t border-gray-700">
                        <div class="flex items-center justify-between mb-2">
                            <label class="text-xs text-gray-400 font-semibold">Tags</label>
                            <button type="button" wire:click="addTagItem"
                                class="text-xs text-indigo-400 hover:text-indigo-300">+ Add Tag</button>
                        </div>
                        @forelse($tags as $index => $tag)
                            <div class="flex items-center gap-2 mb-2">
                                <input wire:model.live.debounce.400ms="tags.{{ $index }}" type="text"
                                    placeholder="Tag"
                                    class="flex-1 bg-gray-800 border border-gray-700 text-white text-xs rounded px-2 py-1.5 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                                <button type="button" wire:click="removeTagItem({{ $index }})"
                                    class="text-gray-500 hover:text-rose-400 p-1.5">x</button>
                            </div>
                        @empty
                            <p class="text-xs text-gray-500 italic">No tags</p>
                        @endforelse
                    </div>

                    <div class="pt-2 border-t border-gray-700">
                        <div class="flex items-center justify-between mb-2">
                            <label class="text-xs text-gray-400 font-semibold">What You'll Get</label>
                            <button type="button" wire:click="addWhatYouGetItem"
                                class="text-xs text-indigo-400 hover:text-indigo-300">+ Add Item</button>
                        </div>
                        @forelse($whatYouGet as $index => $item)
                            <div class="flex items-start gap-2 mb-2">
                                <div class="flex-1 space-y-1">
                                    <input wire:model.live.debounce.400ms="whatYouGet.{{ $index }}.title"
                                        type="text" placeholder="Title (bold)"
                                        class="w-full bg-gray-800 border border-gray-700 text-white text-xs rounded px-2 py-1.5 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                                    <input wire:model.live.debounce.400ms="whatYouGet.{{ $index }}.desc"
                                        type="text" placeholder="Description"
                                        class="w-full bg-gray-800 border border-gray-700 text-white text-xs rounded px-2 py-1.5 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                                </div>
                                <button type="button" wire:click="removeWhatYouGetItem({{ $index }})"
                                    class="text-gray-500 hover:text-rose-400 p-1.5">x</button>
                            </div>
                        @empty
                            <p class="text-xs text-gray-500 italic">No items</p>
                        @endforelse
                    </div>

                    <div class="pt-2 border-t border-gray-700">
                        <div class="flex items-center justify-between mb-2">
                            <label class="text-xs text-gray-400 font-semibold">Inclusions</label>
                            <button type="button" wire:click="addInclusionItem"
                                class="text-xs text-indigo-400 hover:text-indigo-300">+ Add Item</button>
                        </div>
                        @forelse($inclusions as $index => $item)
                            <div class="flex items-start gap-2 mb-2">
                                <div class="flex-1 space-y-1">
                                    <input wire:model.live.debounce.400ms="inclusions.{{ $index }}.title"
                                        type="text" placeholder="Title (bold)"
                                        class="w-full bg-gray-800 border border-gray-700 text-white text-xs rounded px-2 py-1.5 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                                    <input wire:model.live.debounce.400ms="inclusions.{{ $index }}.desc"
                                        type="text" placeholder="Description"
                                        class="w-full bg-gray-800 border border-gray-700 text-white text-xs rounded px-2 py-1.5 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                                </div>
                                <button type="button" wire:click="removeInclusionItem({{ $index }})"
                                    class="text-gray-500 hover:text-rose-400 p-1.5">x</button>
                            </div>
                        @empty
                            <p class="text-xs text-gray-500 italic">No items</p>
                        @endforelse
                    </div>
                </div>
            @endif

            @if ($layout === 'fixed-terms')
                <div class="space-y-3">
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Heading</label>
                        <input wire:model.live.debounce.400ms="heading" type="text"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                    </div>
                    <div class="pt-2 border-t border-gray-700">
                        <div class="text-xs text-gray-400 font-semibold mb-2">Payment Terms</div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="text-xs text-gray-500 font-medium mb-1 block">Row 1 Percentage</label>
                                <input wire:model.live.debounce.400ms="paymentRow1Pct" type="text"
                                    class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 font-medium mb-1 block">Row 1 Description</label>
                                <input wire:model.live.debounce.400ms="paymentRow1Desc" type="text"
                                    class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 font-medium mb-1 block">Row 2 Percentage</label>
                                <input wire:model.live.debounce.400ms="paymentRow2Pct" type="text"
                                    class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 font-medium mb-1 block">Row 2 Description</label>
                                <input wire:model.live.debounce.400ms="paymentRow2Desc" type="text"
                                    class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                            </div>
                        </div>
                    </div>
                    <div class="pt-2 border-t border-gray-700">
                        <div class="text-xs text-gray-400 font-semibold mb-2">Terms Bullets</div>
                        @foreach (range(0, 2) as $idx)
                            <input wire:model.live.debounce.400ms="termsBullets.{{ $idx }}" type="text"
                                placeholder="Bullet {{ $idx + 1 }}"
                                class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 mt-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                        @endforeach
                    </div>
                    <div class="pt-2 border-t border-gray-700">
                        <div class="text-xs text-gray-400 font-semibold mb-2">Client Responsibilities</div>
                        @foreach (range(0, 2) as $idx)
                            <input wire:model.live.debounce.400ms="clientBullets.{{ $idx }}" type="text"
                                placeholder="Item {{ $idx + 1 }}"
                                class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 mt-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                        @endforeach
                    </div>
                    <div class="pt-2 border-t border-gray-700">
                        <div class="text-xs text-gray-400 font-semibold mb-2">Liability</div>
                        @foreach (range(0, 2) as $idx)
                            <input wire:model.live.debounce.400ms="liabilityBullets.{{ $idx }}"
                                type="text" placeholder="Item {{ $idx + 1 }}"
                                class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 mt-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                        @endforeach
                    </div>
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">SLA Text</label>
                        <textarea wire:model.live.debounce.400ms="slaText" rows="3"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 resize-none transition placeholder-gray-600"></textarea>
                    </div>
                </div>
            @endif

            @if ($layout === 'fixed-projects')
                <div class="space-y-3">
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Heading</label>
                        <input wire:model.live.debounce.400ms="heading" type="text"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                    </div>
                    <div class="pt-2 border-t border-gray-700 space-y-2">
                        <label class="text-xs text-gray-400 font-semibold">Project 1</label>
                        <input wire:model.live.debounce.400ms="project1Label" type="text" placeholder="Label"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                        <input wire:model.live.debounce.400ms="project1Url" type="text" placeholder="URL"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                    </div>
                    <div class="pt-2 border-t border-gray-700 space-y-2">
                        <label class="text-xs text-gray-400 font-semibold">Project 2</label>
                        <input wire:model.live.debounce.400ms="project2Label" type="text" placeholder="Label"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                        <input wire:model.live.debounce.400ms="project2Url" type="text" placeholder="URL"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                    </div>
                    <div class="pt-2 border-t border-gray-700 space-y-2">
                        <label class="text-xs text-gray-400 font-semibold">Project 3</label>
                        <input wire:model.live.debounce.400ms="project3Label" type="text" placeholder="Label"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                        <input wire:model.live.debounce.400ms="project3Url" type="text" placeholder="URL"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                    </div>
                    <div class="pt-2 border-t border-gray-700 space-y-2">
                        <label class="text-xs text-gray-400 font-semibold">Portfolio Link</label>
                        <input wire:model.live.debounce.400ms="portfolioLabel" type="text" placeholder="Label"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                        <input wire:model.live.debounce.400ms="portfolioUrl" type="text" placeholder="URL"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                    </div>
                </div>
            @endif

            @if ($layout === 'fixed-organizations')
                <div class="space-y-3">
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Heading</label>
                        <input wire:model.live.debounce.400ms="heading" type="text"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                    </div>
                    <div class="pt-2 border-t border-gray-700">
                        <div class="text-xs text-gray-400 font-semibold mb-2">Organizations</div>
                        @foreach (range(0, 11) as $idx)
                            <input wire:model.live.debounce.400ms="organizations.{{ $idx }}" type="text"
                                placeholder="Organization {{ $idx + 1 }}"
                                class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 mt-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($layout === 'fixed-testimonial')
                <div class="space-y-3">
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Heading</label>
                        <input wire:model.live.debounce.400ms="heading" type="text"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                    </div>
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Testimonial 1</label>
                        <textarea wire:model.live.debounce.400ms="testimonial1" rows="3"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 resize-none transition placeholder-gray-600"></textarea>
                    </div>
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Testimonial 2</label>
                        <textarea wire:model.live.debounce.400ms="testimonial2" rows="3"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 resize-none transition placeholder-gray-600"></textarea>
                    </div>
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Testimonial 3</label>
                        <textarea wire:model.live.debounce.400ms="testimonial3" rows="3"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 resize-none transition placeholder-gray-600"></textarea>
                    </div>
                </div>
            @endif

            @if ($layout === 'fixed-guidance')
                <div class="space-y-3">
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Heading</label>
                        <input wire:model.live.debounce.400ms="heading" type="text"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                    </div>
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">CTA Text</label>
                        <input wire:model.live.debounce.400ms="ctaText" type="text"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                    </div>
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">CTA URL</label>
                        <input wire:model.live.debounce.400ms="ctaUrl" type="text"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                    </div>
                </div>
            @endif

            @if ($layout === 'fixed-contact')
                <div class="space-y-3">
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Heading</label>
                        <input wire:model.live.debounce.400ms="heading" type="text"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                    </div>
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Email</label>
                        <input wire:model.live.debounce.400ms="email" type="text"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                    </div>
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Phone</label>
                        <input wire:model.live.debounce.400ms="phone" type="text"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                    </div>
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Website</label>
                        <input wire:model.live.debounce.400ms="website" type="text"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                    </div>
                </div>
            @endif

            @if ($layout === 'fixed-why-customize')
                <div class="space-y-3">
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Heading</label>
                        <input wire:model.live.debounce.400ms="heading" type="text"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                    </div>
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Body Text</label>
                        <textarea wire:model.live.debounce.400ms="body" rows="6"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 resize-none transition placeholder-gray-600"></textarea>
                    </div>
                </div>
            @endif
        </div>
    </aside>
</div>
