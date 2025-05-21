<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Option extends Model
{
    protected $fillable = ['title', 'description', 'election_id'];

    public function comments():MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    public function votes():HasMany
    {
        return $this->hasMany(Vote::class);
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function getTotalComments()
    {
        return $this->comments()->count();
    }
}
