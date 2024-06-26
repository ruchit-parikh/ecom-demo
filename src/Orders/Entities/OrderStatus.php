<?php

namespace EcomDemo\Orders\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Str;

/**
 * @property int $id
 * @property string $uuid
 * @property string $title
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class OrderStatus extends Model
{
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
}
