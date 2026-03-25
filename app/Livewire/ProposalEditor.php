<?php

namespace App\Livewire;

use App\Models\Proposal;
use Livewire\Component;

class ProposalEditor extends Component
{
    public Proposal $proposal;
    public string $title;
    public string $theme;
    public int $activeSlideIndex = 0;
    public bool $addSlideMenuOpen = false;

    public string $heading = '';
    public string $subheading = '';
    public string $body = '';
    public string $quote = '';
    public string $author = '';
    public string $col1 = '';
    public string $col2 = '';
    public array $cardTitles = [];
    public array $cardBodies = [];
    public string $tagline = '';
    public string $line1 = '';
    public string $line2 = '';
    public string $line3 = '';
    public string $top_heading = '';
    public string $website = '';
    public array $bullets = [];

    protected array $rules = [
        'title' => 'required|string|max:255',
        'theme' => 'required|in:midnight,aurora,slate,rose,forest',
        'heading' => 'nullable|string',
        'subheading' => 'nullable|string',
        'body' => 'nullable|string',
        'quote' => 'nullable|string',
        'author' => 'nullable|string',
        'col1' => 'nullable|string',
        'col2' => 'nullable|string',
    ];

    public function mount(Proposal $proposal): void
    {
        abort_unless($proposal->user_id === auth()->id(), 403);

        $this->proposal = $proposal->load('slides');
        $this->title = $proposal->title;
        $this->theme = $proposal->theme;
        $this->loadSlide(0);
    }

    public function loadSlide(int $index): void
    {
        $this->activeSlideIndex = $index;
        $slide = $this->proposal->slides->get($index);
        if (! $slide) {
            return;
        }

        $c = $slide->content ?? [];
        $this->heading = $c['heading'] ?? '';
        $this->subheading = $c['subheading'] ?? '';
        $this->body = $c['body'] ?? '';
        $this->quote = $c['quote'] ?? '';
        $this->author = $c['author'] ?? '';
        $this->col1 = $c['col1'] ?? '';
        $this->col2 = $c['col2'] ?? '';

        // Strategy cards (optional layout)
        $this->cardTitles = [
            1 => $c['card1_title'] ?? '',
            2 => $c['card2_title'] ?? '',
            3 => $c['card3_title'] ?? '',
            4 => $c['card4_title'] ?? '',
            5 => $c['card5_title'] ?? '',
        ];

        $this->cardBodies = [
            1 => $c['card1_body'] ?? '',
            2 => $c['card2_body'] ?? '',
            3 => $c['card3_body'] ?? '',
            4 => $c['card4_body'] ?? '',
            5 => $c['card5_body'] ?? '',
        ];

        // Fixed template fields (optional layouts)
        $this->tagline = $c['tagline'] ?? '';
        $this->line1 = $c['line1'] ?? '';
        $this->line2 = $c['line2'] ?? '';
        $this->line3 = $c['line3'] ?? '';

        $this->top_heading = $c['top_heading'] ?? '';
        $this->website = $c['website'] ?? '';

        $bullets = $c['bullets'] ?? [];
        $this->bullets = array_values(is_array($bullets) ? $bullets : []);
        for ($i = 0; $i < 6; $i++) {
            $this->bullets[$i] = $this->bullets[$i] ?? '';
        }
    }

    public function updated(string $fullPath, mixed $value): void
    {
        $root = str($fullPath)->before('.')->toString();
        if (in_array($root, [
            'heading',
            'subheading',
            'body',
            'quote',
            'author',
            'col1',
            'col2',
            'cardTitles',
            'cardBodies',
            'tagline',
            'line1',
            'line2',
            'line3',
            'top_heading',
            'website',
            'bullets',
        ], true)) {
            $this->saveCurrentSlide();
        }
    }

    public function saveCurrentSlide(): void
    {
        $slide = $this->proposal->slides->get($this->activeSlideIndex);
        if (! $slide) {
            return;
        }

        $slide->update([
            'content' => [
                'heading' => $this->heading,
                'subheading' => $this->subheading,
                'body' => $this->body,
                'quote' => $this->quote,
                'author' => $this->author,
                'col1' => $this->col1,
                'col2' => $this->col2,
                'card1_title' => $this->cardTitles[1] ?? '',
                'card2_title' => $this->cardTitles[2] ?? '',
                'card3_title' => $this->cardTitles[3] ?? '',
                'card4_title' => $this->cardTitles[4] ?? '',
                'card5_title' => $this->cardTitles[5] ?? '',
                'card1_body' => $this->cardBodies[1] ?? '',
                'card2_body' => $this->cardBodies[2] ?? '',
                'card3_body' => $this->cardBodies[3] ?? '',
                'card4_body' => $this->cardBodies[4] ?? '',
                'card5_body' => $this->cardBodies[5] ?? '',
                'tagline' => $this->tagline,
                'line1' => $this->line1,
                'line2' => $this->line2,
                'line3' => $this->line3,
                'top_heading' => $this->top_heading,
                'website' => $this->website,
                'bullets' => array_values($this->bullets),
            ],
        ]);

        $this->proposal->load('slides');
    }

    public function selectSlide(int $index): void
    {
        $this->saveCurrentSlide();
        $this->loadSlide($index);
    }

    public function addSlide(string $layout = 'content'): void
    {
        $this->addSlideMenuOpen = false;
        $this->saveCurrentSlide();

        $maxOrder = $this->proposal->slides()->max('order') ?? -1;
        $defaults = match ($layout) {
            'title' => ['heading' => 'New Title', 'subheading' => 'Subtitle here'],
            'content' => ['heading' => 'Section Title', 'body' => 'Your content goes here...'],
            'two-col' => ['heading' => 'Two Columns', 'col1' => 'Left column content...', 'col2' => 'Right column content...'],
            'quote' => ['quote' => '"A powerful quote goes here."', 'author' => '- Author Name'],
            'blank' => [],
            'fixed-cover' => [
                'tagline' => 'Your Partner Towards Digital Innovation',
                'line1' => 'WEBSITE',
                'line2' => 'DEVELOPMENT',
                'line3' => 'PROPOSAL',
            ],
            'fixed-executive' => [
                'heading' => 'Executive Summary',
                'body' => 'Your executive summary text...',
            ],
            'fixed-whois' => [
                'top_heading' => 'OUR STRATEGY',
                'heading' => 'Who is\nOdecci?',
                'body' => 'Your who-is text...',
                'website' => 'www.odecci.com',
                'bullets' => [
                    'Client-Centric Solutions',
                    'Data-Driven Decision Making',
                    'Agile Development',
                    'Sustainable Growth',
                    'Collaborative Partnership',
                    'Support & Maintenance',
                ],
            ],
            'fixed-strategy-cards' => [
                'heading' => 'Our Strategy',
                'subheading' => "We understand that every business has\nunique goals for its system, such as:",
                'card1_title' => 'Hand Tailored Solutions',
                'card1_body' => 'Design websites that are uniquely customized...',
                'card2_title' => 'Enhance Client Collaboration',
                'card2_body' => 'Integrate closely with clients throughout...',
                'card3_title' => 'Boost Business Performance',
                'card3_body' => 'Develop a maintenance and support process...',
                'card4_title' => 'Ensure Exceptional User Experience',
                'card4_body' => 'Create intuitive, visually appealing interfaces...',
                'card5_title' => 'Provide Strategic Implementation',
                'card5_body' => 'Support clients with comprehensive strategies...',
            ],
            default => ['heading' => 'New Slide'],
        };

        $slide = $this->proposal->slides()->create([
            'layout' => $layout,
            'content' => $defaults,
            'order' => $maxOrder + 1,
        ]);

        $this->proposal->load('slides');
        $newIndex = $this->proposal->slides->search(fn ($s) => $s->id === $slide->id);
        $this->loadSlide($newIndex !== false ? $newIndex : 0);
    }

    public function deleteSlide(int $index): void
    {
        if ($this->proposal->slides->count() <= 1) {
            return;
        }

        $slide = $this->proposal->slides->get($index);
        if (! $slide) {
            return;
        }

        $slide->delete();
        $this->proposal->load('slides');
        $newIndex = min($index, $this->proposal->slides->count() - 1);
        $this->loadSlide($newIndex);
    }

    public function updateTitle(): void
    {
        $this->validate(['title' => 'required|string|max:255']);
        $this->proposal->update(['title' => $this->title]);
    }

    public function updateTheme(): void
    {
        $this->proposal->update(['theme' => $this->theme]);
        $this->proposal->refresh();
    }

    public function saveAll(): void
    {
        $this->saveCurrentSlide();
        $this->proposal->update([
            'title' => $this->title,
            'theme' => $this->theme,
        ]);

        $this->dispatch('saved');
    }

    public function themeClass(): string
    {
        return match ($this->proposal->theme) {
            'midnight' => 'bg-gradient-to-br from-gray-900 via-indigo-950 to-gray-900 text-white',
            'aurora' => 'bg-gradient-to-br from-purple-950 via-indigo-900 to-teal-950 text-white',
            'slate' => 'bg-gradient-to-br from-slate-700 via-slate-800 to-slate-900 text-white',
            'rose' => 'bg-gradient-to-br from-rose-950 via-pink-900 to-gray-900 text-white',
            'forest' => 'bg-gradient-to-br from-emerald-950 via-green-900 to-gray-900 text-white',
            default => 'bg-gray-900 text-white',
        };
    }

    public function render()
    {
        $this->proposal->load('slides');

        return view('livewire.proposal-editor')->layout('layouts.proposal-editor');
    }
}
