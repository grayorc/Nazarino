<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vote extends Model
{
    protected $fillable = ['user_id', 'option_id', 'vote'];

    public function option():BelongsTo
    {
        return $this->belongsTo(Option::class);
    }

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
