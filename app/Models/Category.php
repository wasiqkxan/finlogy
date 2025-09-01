<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (Auth::check()) {
                $category->user_id = Auth::id();
            }
        });
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function budgets()
    {
        return $this->hasMany(Budget::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
