<?php

namespace EcomDemo\Orders\Entities;

use Carbon\Carbon;
use EcomDemo\Payments\Entities\Payment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Str;

/**
 * @property int $id
 * @property string $uuid
 * @property string $status
 * @property float|null $delivery_fee
 * @property float $amount
 * @property array $address
 * @property array $products
 * @property Payment $payment
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $shipped_at
 */
class Order extends Model
{
    use HasFactory;

    const FREE_DELIVERY_MIN_AMOUNT_ELIGIBLE = 1850;

    const DELIVER_FEE = 15;

    /**
     * @var string[]
     */
    protected $casts = ['shipped_at' => 'datetime', 'address' => 'array', 'products' => 'array'];

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
    public function getStatus(): string
    {
        /** @var OrderStatus $status */
        $status = $this->status;

        return $status->getTitle();
    }

    /**
     * @return BelongsTo
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(OrderStatus::class, 'order_status_id', 'id');
    }

    /**
     * @return string|null
     */
    public function getDeliveryFeeFormatted(): ?string
    {
        return $this->delivery_fee ? number_format($this->delivery_fee, 2) : null;
    }

    /**
     * @return string
     */
    public function getAmountFormatted(): string
    {
        return number_format($this->amount, 2);
    }

    /**
     * @return Carbon|null
     */
    public function shippedAt(): ?Carbon
    {
        return $this->shipped_at ? Carbon::parse($this->shipped_at) : null;
    }

    /**
     * @return Carbon
     */
    public function getCreatedAt(): Carbon
    {
        return Carbon::parse($this->created_at);
    }

    /**
     * @return array
     */
    public function getAddress(): array
    {
        return $this->address;
    }

    /**
     * @return array
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * @return Payment
     */
    public function getPayment(): Payment
    {
        return $this->payment;
    }

    /**
     * @return BelongsTo
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id', 'id');
    }
}
