<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use phpDocumentor\Reflection\PseudoTypes\Numeric_;

class Election extends Model
{
    protected $fillable = ['title', 'description', 'end_date', 'has_comment', 'is_revocable', 'is_open', 'is_public', 'user_id'];

    public function comments():MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }


    public function options():HasMany
    {
        return $this->hasMany(Option::class);
    }

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function votes():HasMany
    {
        return $this->hasMany(Vote::class);
    }

    public function userCount():null|int
    {
        return $this->votes()->whereIn('option_id', $this->options->pluck('id'))
            ->distinct('user_id')
            ->count();
    }
}
