<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    use HasFactory;

    protected $fillable = [
        'election_id',
        'user_id',
        'status',
        'accepted_at'
    ];

    /**
     * Get the election that owns the invite.
     */
    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    /**
     * Get the user that received the invite.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if a user was invited to a specific election.
     *
     * @param  int  $userId
     * @param  int  $electionId
     * @return bool
     */
    public static function wasInvited($userId, $electionId)
    {
        return static::where('user_id', $userId)
                     ->where('election_id', $electionId)
                     ->exists();
    }
}
