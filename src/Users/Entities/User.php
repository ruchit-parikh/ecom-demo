<?php

namespace EcomDemo\Users\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Str;

/**
 * @property int $id
 * @property string $uuid
 * @property string $first_name
 * @property string $last_name
 * @property boolean $is_admin
 * @property string $email
 * @property string $email_verified_at
 * @property string $password
 * @property string $address
 * @property string $phone_number
 * @property boolean $is_marketing
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $last_login_at
 */
class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

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
     * @var array<string, string>
     */
    protected $casts = ['last_login_at' => 'date'];

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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->uuid = Str::orderedUuid();
        });
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }
}
