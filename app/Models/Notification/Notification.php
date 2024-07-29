<?php

namespace App\Models\Notification;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'notifiable_type',
        'data',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
    ];
}