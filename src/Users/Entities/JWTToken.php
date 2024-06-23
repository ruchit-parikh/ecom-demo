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
 * @method static void store()
 */
class JWTToken extends Model
{
    use HasFactory;

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'restrictions' => 'array',
        'permissions'  => 'array',
        'expires_at'   => 'date',
        'last_used_at' => 'date',
        'refreshed_at' => 'date',
    ];
}
