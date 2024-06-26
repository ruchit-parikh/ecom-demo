<?php

namespace App\Http\Resources\Files;

use EcomDemo\Files\Entities\File;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property File $resource
 */
class FileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->resource->getUuid(),
            'name' => $this->resource->getName(),
            'path' => $this->resource->getPublicPath(),
            'size' => $this->resource->getSize(),
            'type' => $this->resource->getType(),
        ];
    }
}
