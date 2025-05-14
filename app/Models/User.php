<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Number;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'is_admin'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's full name.
     */
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Get the display name (either username or full name).
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->getFullNameAttribute() ?: $this->username;
    }

    public function IsAdmin()
    {
        return $this->is_admin;
    }

    public function Image():MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function comments():MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function votes():HasMany
    {
        return $this->hasMany(Vote::class);
    }

    public function elections():HasMany
    {
        return $this->hasMany(Election::class);
    }

    public function roles() {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    public function hasRole($roleName): bool
    {
        return $this->roles->contains('name', $roleName);
    }

    public function hasAnyRole(): bool
    {
        return $this->roles()->exists();
    }

    public function permissions() {
        return $this->belongsToMany(Permission::class, 'permission_user', 'user_id', 'permission_id');
    }

    public function roleHasPermission($permission): bool
    {
        return $this->roles()->whereHas('permissions', function ($query) use ($permission) {
            $query->where('name', $permission);
        })->exists();
    }

    public function userHasPermission($permission): bool
    {
        return $this->permissions()->where('name', $permission)->exists();
    }

    public function hasPermission($permission): bool
    {
        return $this->userHasPermission($permission) || $this->roleHasPermission($permission);
    }

    public function userVote($option_id)
    {
        return $this->votes()->where('option_id', $option_id)->value('vote') ?? null;
    }

    public function subscriptions()
    {
        return $this->hasMany(SubscriptionUser::class);
    }

    public function receiptUsers()
    {
        return $this->hasMany(ReceiptUser::class);
    }

    public function receipts()
    {
        return $this->belongsToMany(Receipt::class, 'receipt_users')
            ->withPivot('amount', 'status')
            ->withTimestamps();
    }

    public function hasSubFeature($subFeatureKey): bool
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->whereHas('subscriptionTier.subFeatures', function ($query) use ($subFeatureKey) {
                $query->where('key', $subFeatureKey);
            })
            ->exists();
    }

    public function subEnd()
    {
        return $this->subscriptions()->where('status', 'active')->first()->ends_at ?? 0 ;
    }

    public function hasSubscription(): bool
    {
        return $this->subscriptions()->where('status', 'active')->exists();
    }

    public function getTotalVotes()
    {
        return array_sum($this->elections()->get()->map(function ($election) {
            return $election->getTotalVotes();
        })->toArray());
    }

    public function getTotalComments()
    {
        return $this->elections()->get()->map(function ($election) {
            return $election->options()->get()->map(function ($option) {
                return $option->comments()->count();
            });
        })->flatten()->sum();
    }

    public function getRemainingDays()
    {
        $endDate = Carbon::parse($this->subEnd());
        return max(Number::format(now()->diffInDays($endDate), precision: 0), 0);
    }

    public function totalElections()
    {
        return $this->elections()->count();
    }

    public function totalActiveElections()
    {
        return $this->elections()
            ->where('is_open', true)
            ->where(function ($query) {
                $query->where('is_public', true)
                    ->orWhere('end_date', '>=', Carbon::now())
                    ->orWhere('end_date', null);
            })->count();
    }

    public function totalInactiveElections()
    {
        return $this->totalElections() - $this->totalActiveElections();
    }
}
