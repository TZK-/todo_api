<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    protected $fillable = [
        'title', 'description', 'ended'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
