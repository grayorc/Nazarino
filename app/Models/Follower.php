<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    use HasFactory;

    protected $table = 'followers';
    protected $fillable = ['user_id', 'follower_id'];

    public $incrementing = false;
    protected $primaryKey = ['user_id', 'follower_id'];
    protected $keyType = 'array';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function follower()
    {
        return $this->belongsTo(User::class, 'follower_id');
    }
}
