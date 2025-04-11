<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Image extends Model
{
    protected $fillable = ['path','imageable_id','imageable_type'];
    public function imageable():morphTo
    {
        return $this->morphTo();
    }
}
