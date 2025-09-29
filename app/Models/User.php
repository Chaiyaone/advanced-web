<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'level'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function isAdmin()
    {
        return $this->level === 'admin';
    }

    public function isEmployee()
    {
        return $this->level === 'employee';
    }

    public function isCustomer()
    {
        return $this->level === 'customer';
    }

    // Method สำหรับตรวจสอบว่า user มี role ที่กำหนดหรือไม่
    public function hasRole($role)
    {
        return $this->level === $role;
    }

    // Method สำหรับดึงหน้า home ตาม role
    public function getHomeRoute()
    {
        switch($this->level) {
            case 'admin':
            case 'employee':
                return '/product';
            case 'customer':
                return '/home';
            default:
                return '/home';
        }
    }
}
