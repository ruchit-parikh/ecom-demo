<?php

namespace EcomDemo\Users\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string $unique_id
 * @property string $token_title
 * @property array $restrictions
 * @property array $permissions
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $expires_at
 * @property Carbon $last_used_at
 * @property Carbon $refreshed_at
 */
class JWTToken extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $guarded = ['id'];

    /**
     * @var string
     */
    protected $table = 'jwt_tokens';

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'restrictions' => 'array',
        'permissions'  => 'array',
        'expires_at'   => 'datetime',
        'last_used_at' => 'datetime',
        'refreshed_at' => 'datetime',
    ];

    /**
     * @param Carbon|null $expiry
     *
     * @return $this
     */
    public function setExpiry(?Carbon $expiry): self
    {
        $this->expires_at = $expiry;

        return $this;
    }

    /**
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->expires_at && !$this->expires_at->isAfter(Carbon::now());
    }
}
