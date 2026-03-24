<?php

namespace App\Livewire;

use App\Models\ProposalSlide as SlideModel;
use Livewire\Component;

class ProposalSlide extends Component
{
    /** @var SlideModel */
    public SlideModel $slide;

    public bool $mini = false;

    /** Merged content for live preview while editing (optional). */
    public ?array $contentOverride = null;

    /** Tailwind classes for slide background/theme (optional). */
    public string $themeClass = '';

    public function render()
    {
        return view('livewire.proposal-slide');
    }
}
