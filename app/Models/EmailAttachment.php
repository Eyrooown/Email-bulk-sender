<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailAttachment extends Model
{
    protected $fillable = ['email_id', 'filename', 'path', 'is_proposal'];

    protected $casts = [
        'is_proposal' => 'boolean',
    ];

    public function email()
    {
        return $this->belongsTo(Email::class);
    }
}
