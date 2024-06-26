<?php

namespace EcomDemo\Payments\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Str;

/**
 * @property int $id
 * @property string $uuid
 * @property string $type
 * @property array $details
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Payment extends Model
{
    /**
     * @var string[]
     */
    protected $casts = ['details' => 'array'];

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
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getDetails(): array
    {
        return $this->details;
    }
}
