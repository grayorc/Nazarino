<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Option extends Model
{
    protected $fillable = ['title', 'description', 'election_id'];

    public function comments():MorphToMany
    {
        return $this->morphToMany(Comment::class, 'commentable');
    }

    public function elections():HasMany
    {
        return $this->hasMany(Election::class);
    }

    public function votes():HasMany
    {
        return $this->hasMany(Vote::class);
    }
}
