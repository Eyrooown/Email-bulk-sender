<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailRecipient extends Model
{
    protected $fillable = ['email_id', 'email', 'status'];

    public function email()
    {
        return $this->belongsTo(Email::class);
    }
}
