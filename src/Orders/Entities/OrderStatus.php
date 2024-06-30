<?php

namespace EcomDemo\Orders\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

/**
 * @property int $id
 * @property string $uuid
 * @property string $title
 * @property string $slug
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class OrderStatus extends Model
{
    use HasFactory;

    public const STATUS_OPEN            = 'open';
    public const STATUS_PENDING_PAYMENT = 'pending_payment';
    public const STATUS_PAID            = 'paid';
    public const STATUS_SHIPPED         = 'shipped';
    public const STATUS_CANCELLED       = 'cancelled';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($file) {
            $file->uuid = Str::orderedUuid();
        });
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return array
     */
    public static function getPredefined(): array
    {
        return [static::STATUS_OPEN, static::STATUS_PENDING_PAYMENT, static::STATUS_PAID, static::STATUS_SHIPPED, static::STATUS_CANCELLED];
    }

    /**
     * @return bool
     */
    public function isShipped(): bool
    {
        return $this->slug === static::STATUS_SHIPPED;
    }

    /**
     * @return bool
     */
    public function isPaid(): bool
    {
        return $this->slug === static::STATUS_PAID;
    }
}
