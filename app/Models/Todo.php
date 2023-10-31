<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;

    protected $fillable = [
        'title',
        'description',
        'finished',
        'user_id'
    ];

    protected $casts = [
        'created_at' => 'datetime'
    ];
}
