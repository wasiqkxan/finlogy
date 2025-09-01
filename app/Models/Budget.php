<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'amount',
        'start_date',
        'end_date',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($budget) {
            if (Auth::check()) {
                $budget->user_id = Auth::id();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
