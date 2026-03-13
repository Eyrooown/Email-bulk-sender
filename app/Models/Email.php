<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Email extends Model
{
    use SoftDeletes;
    protected $fillable = ['user_id', 'subject', 'body', 'status'];

    public function recipients()
    {
        return $this->hasMany(EmailRecipient::class);
    }

    public function attachments()
    {
        return $this->hasMany(EmailAttachment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
