<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\Validatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Validatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'alamat',
        'no_hp',
        'role',
        'status',
    ];

    public const ROLE = [
        'admin' => 'Admin',
        'seller' => 'Seller',
        'buyer' => 'Buyer',
    ];

    public const STATUS = [
        'on' => 'Aktif',
        'off' => 'Tidak Aktif',
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
        'password' => 'hashed',
    ];

    public function rules($scenario = null)
    {
        $scenarios = [
            null => [
                'password' => [
                    'required',
                    'string',
                    Password::min(8)
                        ->mixedCase()
                        ->numbers()
                        ->symbols(),
                    'max:256'
                ],
                'name' => 'required|max:256',
                'email' => [
                    'required',
                    Rule::unique($this->getTable())->ignore($this),
                    'email',
                    'max:256'
                ],
                'role' => 'required',
                'status' => 'required'
            ]
        ];

        $rules = $scenarios[$scenario] ?? $scenarios[null];
        return $rules;
    }

    public function labels()
    {
        return [
            'username' => 'Username',
            'password' => 'Password',
            'name' => 'Nama',
            'email' => 'Email',
            'role' => 'Role',
            'status' => 'Status',
        ];
    }


    public function getStatusValAttribute()
    {
        return self::STATUS[$this->status];
    }

    public function getRoleValAttribute()
    {
        return self::ROLE[$this->role];
    }

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class);
    }

    public function pesanan_detail()
    {
        return $this->hasMany(PesananDetail::class, 'user_id', 'id');
    }

    public function getBarangKeranjangAttribute()
    {
        return $this->pesanan_detail->where('pesanan_id', null);
    }
}
