<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubClient extends Model
{
    use HasFactory;

    // Define the table name
    protected $table = 'sub_clients';

    protected $fillable = [
        'client_name',
        'email',
        'phone_number',
        'alternate_phone_number',
        'address',
        'status',
        'username',
        'password',
    ];
    protected $hidden = ['password'];
}