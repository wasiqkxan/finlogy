<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'current_balance',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($account) {
            if (Auth::check()) {
                $account->user_id = Auth::id();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * The users that have access to the account.
     */
    public function sharedWith(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'account_shares');
    }
}
