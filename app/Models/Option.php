<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Option extends Model
{
    public function comments():MorphToMany
    {
        return $this->morphToMany(Comment::class, 'commentable');
    }
}
