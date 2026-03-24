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
    }

    public function updated(string $fullPath, mixed $value): void
    {
        $root = str($fullPath)->before('.')->toString();
        if (in_array($root, ['heading', 'subheading', 'body', 'quote', 'author', 'col1', 'col2'], true)) {
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
