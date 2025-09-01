<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Casts\Attribute;

class BankConnection extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'bank_name',
        'access_token',
        'last_sync_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'last_sync_at' => 'datetime',
    ];

    /**
     * Get the user that owns the bank connection.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Interact with the access token.
     */
    protected function accessToken(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Crypt::decryptString($value),
            set: fn ($value) => Crypt::encryptString($value),
        );
    }
}
