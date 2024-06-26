<?php

namespace App\Http\Resources\Users;

use App\Http\Resources\Files\FileResource;
use EcomDemo\Users\Entities\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property User $resource
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid'          => $this->resource->getUuid(),
            'first_name'    =>  $this->resource->getFirstName(),
            'last_name'     => $this->resource->getLastName(),
            'email'         => $this->resource->getEmail(),
            'address'       => $this->resource->getAddress(),
            'phone_number'  => $this->resource->getPhoneNumber(),
            'is_marketing'  => $this->resource->isMarketing(),
            'avatar'        => FileResource::make($this->resource->getAvatar()),
            'last_login_at' => $this->resource->getLastLoggedInAt() ? $this->resource->getLastLoggedInAt()->toDayDateTimeString() : 'N/A'
        ];
    }
}
