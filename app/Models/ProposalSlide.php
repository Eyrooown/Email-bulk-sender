<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProposalSlide extends Model
{
    protected $fillable = ['proposal_id', 'layout', 'content', 'order'];

    protected $casts = [
        'content' => 'array',
    ];

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }

    public function getContentValue(string $key, string $default = ''): string
    {
        return $this->content[$key] ?? $default;
    }
}
