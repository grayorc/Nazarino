<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Election extends Model
{
    public function comments():MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
