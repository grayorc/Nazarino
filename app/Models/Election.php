<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Election extends Model
{
    protected $fillable = ['title', 'description', 'end_date', 'has_comment', 'is_revocable', 'is_open', 'is_public', 'is_multivote', 'user_id'];

    public function comments():MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function options():HasMany
    {
        return $this->hasMany(Option::class);
    }

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
