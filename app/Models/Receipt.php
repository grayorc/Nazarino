<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Receipt extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'receipt_number',
        'user_id',
        'subscription_user_id',
        'amount',
        'currency',
        'status',
        'payment_method',
        'meta_data',
        'paid_at'
    ];

    protected $casts = [
        'meta_data' => 'array',
        'paid_at' => 'datetime'
    ];

    public function subscriptionUser(): BelongsTo
    {
        return $this->belongsTo(SubscriptionUser::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
