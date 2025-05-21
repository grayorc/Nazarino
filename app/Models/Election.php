<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

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

    public function userCount():null|int
    {
        return Vote::whereIn('option_id', $this->options->pluck('id'))
            ->distinct('user_id')
            ->count();
    }

    public function getTotalVotes(): int
    {
        return Vote::whereIn('option_id', $this->options->pluck('id'))->count();
    }

    public function aiAnalysis(): HasOne
    {
        return $this->hasOne(AiAnalysis::class);
    }

    public function invites(): HasMany
    {
        return $this->hasMany(Invite::class);
    }

    public function getTotalComments(): int
    {
        return $this->options->sum(function ($option) {
            return $option->getTotalComments();
        });
    }

    public function getVotesPerOption(): Collection
    {
        return $this->options()
            ->withCount('votes')
            ->get()
            ->map(function ($option) {
                return [
                    'option_id' => $option->id,
                    'title' => $option->title,
                    'votes_count' => $option->votes_count
                ];
            });
    }

    public function getDetailedVoteStats(): Collection
    {
        return $this->options()
            ->withCount([
                'votes',
                'votes as upvotes_count' => function ($query) {
                    $query->where('vote', 1);
                },
                'votes as downvotes_count' => function ($query) {
                    $query->where('vote', -1);
                }
            ])
            ->get()
            ->map(function ($option) {
                return [
                    'option_id' => $option->id,
                    'title' => $option->title,
                    'total_votes' => $option->votes_count,
                    'upvotes' => $option->upvotes_count,
                    'downvotes' => $option->downvotes_count
                ];
            });
    }
}
