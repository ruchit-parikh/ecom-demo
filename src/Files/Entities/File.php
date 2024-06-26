<?php

namespace EcomDemo\Files\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Str;

/**
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property string $path
 * @property string $size
 * @property string $type
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class File extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($file) {
            $file->uuid = Str::orderedUuid();
        });
    }
}
