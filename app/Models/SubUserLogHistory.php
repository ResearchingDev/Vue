<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubUserLogHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ip_address',
        'user_type',
        'login_at',
        'login_source',
        'browser_details',
        'status',
        'created_by',
        'logout_at',
        'logout_source',
        'updated_by',
    ];
}
