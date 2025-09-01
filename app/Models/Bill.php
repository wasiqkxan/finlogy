<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Bill extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'description',
        'amount',
        'due_date',
        'frequency',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($bill) {
            if (Auth::check()) {
                $bill->user_id = Auth::id();
            }
        });
    }

    /**
     * Get the user that owns the bill.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
