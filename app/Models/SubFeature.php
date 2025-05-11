<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SubFeature extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'key',
    ];

    public function subscriptionTiers(): BelongsToMany
    {
        return $this->belongsToMany(SubscriptionTier::class, 'subscription_tier_sub_features')
            ->withTimestamps();
    }
}
