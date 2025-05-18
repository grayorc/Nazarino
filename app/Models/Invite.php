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

}
