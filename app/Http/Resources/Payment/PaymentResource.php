<?php

namespace App\Http\Resources\Payment;

use EcomDemo\Payments\Entities\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Payment $resource
 */
class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid'    => $this->resource->getUuid(),
            'type'    => $this->resource->getType(),
            'details' => $this->resource->getDetails(),
        ];
    }
}
