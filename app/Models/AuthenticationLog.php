<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthenticationLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'table',
        'action',
    ];
}
