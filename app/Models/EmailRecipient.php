<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailRecipient extends Model
{
    protected $fillable = ['email_id', 'email', 'status'];

    public function campaign()
    {
        return $this->belongsTo(Email::class, 'email_id');
    }
}
