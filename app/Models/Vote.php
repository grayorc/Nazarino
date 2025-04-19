<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Vote extends Model
{
    protected $fillable = ['user_id', 'option_id', 'vote', 'election_id'];

    public function option():BelongsTo
    {
        return $this->belongsTo(Option::class);
    }

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function election():BelongsTo
    {
        return $this->belongsTo(Election::class);
    }


}
