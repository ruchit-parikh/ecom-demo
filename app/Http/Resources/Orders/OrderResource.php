<?php

namespace App\Http\Resources\Orders;

use App\Http\Resources\Payment\PaymentResource;
use EcomDemo\Orders\Entities\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Order $resource
 */
class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid'         => $this->resource->getUuid(),
            'status'       => $this->resource->getStatus(),
            'payment'      => PaymentResource::make($this->resource->getPayment()),
            'products'     => $this->resource->getProducts(),
            'address'      => $this->resource->getAddress(),
            'delivery_fee' => $this->resource->getDeliveryFeeFormatted(),
            'amount'       => $this->resource->getAmountFormatted(),
            'shipped_at'   => $this->resource->shippedAt() ? $this->resource->shippedAt()->toDayDateTimeString() : 'N/A',
            'placed_at'    => $this->resource->getCreatedAt()->toDayDateTimeString(),
        ];
    }
}
