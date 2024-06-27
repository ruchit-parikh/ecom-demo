<?php

namespace EcomDemo\Users\Entities;

use Carbon\Carbon;
use EcomDemo\Files\Entities\File;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
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
 * @property string|null $avatar
 * @property File|null $avatarFile
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $last_login_at
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'last_login_at'     => 'datetime',
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

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->is_admin;
    }

    /**
     * @param string|null $password
     *
     * @return bool
     */
    public function isPasswordValid(?string $password): bool
    {
        return Hash::check($password, $this->password);
    }

    /**
     * @return $this
     */
    public function refreshLastLoggedIn(): self
    {
        $this->last_login_at = Carbon::now();

        return $this;
    }

    /**
     * @param string $firstName
     *
     * @return $this
     */
    public function setFirstName(string $firstName): self
    {
        $this->first_name = $firstName;

        return $this;
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = Hash::make($password);

        return $this;
    }

    /**
     * @param string $lastName
     *
     * @return $this
     */
    public function setLastName(string $lastName): self
    {
        $this->last_name = $lastName;

        return $this;
    }

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @param string $address
     *
     * @return $this
     */
    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @param string $phoneNumber
     *
     * @return $this
     */
    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phone_number = $phoneNumber;

        return $this;
    }

    /**
     * @param bool $enabled
     *
     * @return $this
     */
    public function setMarketingPreference(bool $enabled): self
    {
        $this->is_marketing = $enabled;

        return $this;
    }

    /**
     * @param string|null $avatarUuid
     *
     * @return $this
     */
    public function setAvatarUuid(?string $avatarUuid): self
    {
        $this->avatar = $avatarUuid;

        return $this;
    }

    /**
     * @param bool $isAdmin
     *
     * @return $this
     */
    public function asAdmin(bool $isAdmin): self
    {
        $this->is_admin = $isAdmin;

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->first_name;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->last_name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return $this->phone_number;
    }

    /**
     * @return bool
     */
    public function isMarketing(): bool
    {
        return $this->is_marketing;
    }

    /**
     * @return Carbon|null
     */
    public function getLastLoggedInAt(): ?Carbon
    {
        return $this->last_login_at ? Carbon::parse($this->last_login_at) : null;
    }

    /**
     * @return File|null
     */
    public function getAvatar(): ?File
    {
        return $this->avatarFile;
    }

    /**
     * @return BelongsTo
     */
    public function avatarFile(): BelongsTo
    {
        return $this->belongsTo(File::class, 'avatar', 'uuid');
    }
}
