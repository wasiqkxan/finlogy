<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable = [
        'file_path',
        'file_name',
        'mime_type',
    ];

    public function attachable()
    {
        return $this->morphTo();
    }
}
