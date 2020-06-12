<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'subscription_status'
    ];


    protected $casts = [
        'sync_at' => 'datetime',
    ];
}
