<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'category_id',
        'type',
        'description',
        'amount',
        'transaction_date',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            if (Auth::check()) {
                $transaction->user_id = Auth::id();
            }
        });
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
