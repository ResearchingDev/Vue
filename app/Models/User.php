<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;
    protected $table = 'sub_users';  // Use your custom table name here


    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'email',
        'password',
        'secondary_password',  // Make sure this is included
        'first_name',
        'last_name',
        'phone_number',
        'alter_phone_number',
        'status',
        'user_type',
        'can_login',
        'username',
        'role_id',
        'client_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'secondary_password',
        'remember_token',
        'created_at',
        'updated_at',
        'deleted_at',
        'updated_by',
        'created_by'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function role()
    {
        return $this->belongsTo(SubUserRole::class, 'role_id', 'id');
    }
    public function subClient()
    {
        return $this->belongsTo(SubClient::class, 'client_id', 'id');
    }
}
