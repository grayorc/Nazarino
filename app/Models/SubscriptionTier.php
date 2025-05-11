<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SubscriptionTier extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'price'
    ];

    // Relationship with sub_features
    public function subFeatures(): BelongsToMany
    {
        return $this->belongsToMany(SubFeature::class, 'subscription_tier_sub_features')
            ->withTimestamps();
    }

    // Relationship with subscription users
    public function subscriptionUsers(): HasMany
    {
        return $this->hasMany(SubscriptionUser::class);
    }

    public function hasSubFeature($subFeatureId): bool
    {
        return $this->subFeatures()
            ->where('sub_features.id', $subFeatureId)
            ->exists();
    }

    public function hasSubFeatureByKey(string $key): bool
    {
        return $this->subFeatures()
            ->where('key', $key)
            ->exists();
    }
}
