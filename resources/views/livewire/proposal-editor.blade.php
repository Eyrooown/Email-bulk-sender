<div class="flex h-screen overflow-hidden bg-gray-950" wire:poll.5000ms="saveAll">
    <div class="fixed top-0 left-0 right-0 z-50 h-12 bg-gray-900 border-b border-gray-800 flex items-center px-4 gap-3">
        <a href="{{ route('proposal') }}"
            class="flex items-center gap-1.5 text-gray-400 hover:text-white transition mr-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </svg>
            <span class="w-5 h-5 rounded bg-indigo-500 flex items-center justify-center text-xs font-bold text-white">P</span>
        </a>

        <input type="text"
            wire:model.blur="title"
            wire:change="updateTitle"
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

        <a href="{{ route('proposal.export.pdf', $proposal) }}"
            target="_blank"
            class="inline-flex items-center gap-1.5 bg-indigo-500 hover:bg-indigo-400 text-white text-xs font-semibold px-3 py-1.5 rounded transition-colors">
            Export PDF
        </a>

        <div id="toast"
            class="fixed bottom-6 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-4 py-2 rounded-full shadow-lg opacity-0 translate-y-2 transition-all duration-300 pointer-events-none z-[100]">
            Saved
        </div>
    </div>

    <aside class="fixed left-0 top-12 bottom-0 w-[200px] bg-gray-900 border-r border-gray-800 overflow-y-auto z-40 flex flex-col">
        <div class="p-2 space-y-1.5">
            @foreach ($proposal->slides as $i => $slide)
                <button wire:click="selectSlide({{ $i }})"
                    class="group relative w-full rounded-lg overflow-hidden border-2 transition-all {{ $activeSlideIndex === $i ? 'border-indigo-500 shadow-lg shadow-indigo-900/30' : 'border-transparent hover:border-gray-600' }}"
                    title="Slide {{ $i + 1 }}">
                    <div class="aspect-video w-full text-left overflow-hidden pointer-events-none relative {{ $this->themeClass() }}"
                        style="transform: scale(1); font-size: 3px;">
                        @include('livewire.partials.proposal-slide-content', ['slide' => $slide, 'mini' => true])
                    </div>

                    <span class="absolute bottom-1 right-1.5 text-[8px] text-white/40 font-mono">{{ $i + 1 }}</span>

                    @if ($proposal->slides->count() > 1)
                        <button wire:click.stop="deleteSlide({{ $i }})"
                            class="absolute top-0.5 right-0.5 w-4 h-4 bg-gray-900/80 rounded text-gray-400 hover:text-rose-400 hidden group-hover:flex items-center justify-center transition">
                            x
                        </button>
                    @endif
                </button>
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
                        'title' => 'Title Slide',
                        'content' => 'Text & Content',
                        'two-col' => 'Two Columns',
                        'quote' => 'Quote',
                        'blank' => 'Blank',
                        'fixed-cover' => 'Fixed Cover (template)',
                        'fixed-executive' => 'Fixed Executive (template)',
                        'fixed-whois' => 'Fixed Who-is (template)',
                        'fixed-strategy-cards' => 'Fixed Strategy Cards (template)',
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
            @php
                $previewContent = array_merge($currentSlide->content ?? [], [
                    'heading' => $heading,
                    'subheading' => $subheading,
                    'body' => $body,
                    'quote' => $quote,
                    'author' => $author,
                    'col1' => $col1,
                    'col2' => $col2,
                    'card1_title' => $cardTitles[1] ?? '',
                    'card2_title' => $cardTitles[2] ?? '',
                    'card3_title' => $cardTitles[3] ?? '',
                    'card4_title' => $cardTitles[4] ?? '',
                    'card5_title' => $cardTitles[5] ?? '',
                    'card1_body' => $cardBodies[1] ?? '',
                    'card2_body' => $cardBodies[2] ?? '',
                    'card3_body' => $cardBodies[3] ?? '',
                    'card4_body' => $cardBodies[4] ?? '',
                    'card5_body' => $cardBodies[5] ?? '',
                'tagline' => $tagline,
                'line1' => $line1,
                'line2' => $line2,
                'line3' => $line3,
                'top_heading' => $top_heading,
                'website' => $website,
                'bullets' => $bullets,
                ]);
            @endphp
            <div class="w-full max-w-4xl">
                <div class="relative w-full" style="padding-top: 56.25%;">
                    <div class="absolute inset-0 rounded-xl overflow-hidden shadow-2xl shadow-black/60 {{ $this->themeClass() }}">
                        @include('livewire.partials.proposal-slide-content', [
                            'slide' => $currentSlide,
                            'mini' => false,
                            'contentOverride' => $previewContent,
                        ])
                    </div>
                </div>
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
                        <textarea wire:model.live.debounce.400ms="heading"
                            rows="2"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 resize-none transition placeholder-gray-600"></textarea>
                    </div>
                @endif

                @if ($layout === 'title')
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Subtitle</label>
                        <textarea wire:model.live.debounce.400ms="subheading"
                            rows="2"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 resize-none transition placeholder-gray-600"></textarea>
                    </div>
                @endif

                @if ($layout === 'content')
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Body Text</label>
                        <textarea wire:model.live.debounce.400ms="body"
                            rows="7"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 resize-none transition placeholder-gray-600"></textarea>
                    </div>
                @endif

                @if ($layout === 'two-col')
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Left Column</label>
                        <textarea wire:model.live.debounce.400ms="col1"
                            rows="5"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 resize-none transition placeholder-gray-600"></textarea>
                    </div>
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Right Column</label>
                        <textarea wire:model.live.debounce.400ms="col2"
                            rows="5"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 resize-none transition placeholder-gray-600"></textarea>
                    </div>
                @endif

                @if ($layout === 'quote')
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Quote</label>
                        <textarea wire:model.live.debounce.400ms="quote"
                            rows="4"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 resize-none transition placeholder-gray-600"></textarea>
                    </div>
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Attribution</label>
                        <input wire:model.live.debounce.400ms="author"
                            type="text"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                    </div>
                @endif
            @endif

            @if ($layout === 'fixed-cover')
                <div class="space-y-3">
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Tagline</label>
                        <input wire:model.live.debounce.400ms="tagline"
                            type="text"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                    </div>
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Line 1</label>
                        <input wire:model.live.debounce.400ms="line1"
                            type="text"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                    </div>
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Line 2</label>
                        <input wire:model.live.debounce.400ms="line2"
                            type="text"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                    </div>
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Line 3</label>
                        <input wire:model.live.debounce.400ms="line3"
                            type="text"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                    </div>
                </div>
            @endif

            @if ($layout === 'fixed-executive')
                <div>
                    <label class="text-xs text-gray-400 font-medium mb-1.5 block">Heading</label>
                    <textarea wire:model.live.debounce.400ms="heading"
                        rows="2"
                        class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 resize-none transition placeholder-gray-600"></textarea>
                </div>
                <div class="mt-4">
                    <label class="text-xs text-gray-400 font-medium mb-1.5 block">Body Text</label>
                    <textarea wire:model.live.debounce.400ms="body"
                        rows="7"
                        class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 resize-none transition placeholder-gray-600"></textarea>
                </div>
            @endif

            @if ($layout === 'fixed-whois')
                <div class="space-y-3">
                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Top heading</label>
                        <input wire:model.live.debounce.400ms="top_heading"
                            type="text"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                    </div>

                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Main heading</label>
                        <textarea wire:model.live.debounce.400ms="heading"
                            rows="2"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 resize-none transition placeholder-gray-600"></textarea>
                    </div>

                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Body</label>
                        <textarea wire:model.live.debounce.400ms="body"
                            rows="7"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 resize-none transition placeholder-gray-600"></textarea>
                    </div>

                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Website</label>
                        <input wire:model.live.debounce.400ms="website"
                            type="text"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 transition placeholder-gray-600" />
                    </div>

                    <div class="pt-2 border-t border-gray-700">
                        <div class="text-xs text-gray-400 font-semibold mb-2">Bullets (6 items)</div>
                        @foreach (range(0, 5) as $idx)
                            <div class="mt-2">
                                <label class="text-xs text-gray-400 font-medium mb-1.5 block">Bullet {{ $idx + 1 }}</label>
                                <input wire:model.live.debounce.400ms="bullets.{{ $idx }}"
                                    type="text"
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
                        <textarea wire:model.live.debounce.400ms="heading"
                            rows="2"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 resize-none transition placeholder-gray-600"></textarea>
                    </div>

                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1.5 block">Subheading</label>
                        <textarea wire:model.live.debounce.400ms="subheading"
                            rows="3"
                            class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 resize-none transition placeholder-gray-600"></textarea>
                    </div>

                    @foreach (range(1, 5) as $i)
                        @php
                            $titleKey = "card{$i}_title";
                            $bodyKey = "card{$i}_body";
                        @endphp

                        <div class="pt-2 border-t border-gray-700">
                            <label class="text-xs text-gray-400 font-medium mb-1.5 block">Card {{ $i }} Title</label>
                            <textarea
                                wire:model.live.debounce.400ms="cardTitles.{{ $i }}"
                                rows="2"
                                class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 resize-none transition placeholder-gray-600"></textarea>

                            <label class="text-xs text-gray-400 font-medium mb-1.5 block mt-3">Card {{ $i }} Description</label>
                            <textarea
                                wire:model.live.debounce.400ms="cardBodies.{{ $i }}"
                                rows="3"
                                class="w-full bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-indigo-400 resize-none transition placeholder-gray-600"></textarea>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </aside>
</div>
