<?php

namespace App\Models;

use App\Support\OrderNumber;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    public $table = 'order';

    protected $fillable = [
        'order_number',
        'total_price',
        'status',
        'comment',
        'session_id',
        'created_by',
        'updated_by',
    ];

    protected static function booted(): void
    {
        static::creating(function (Order $order): void {
            if ($order->getAttribute('order_number') !== null) {
                return;
            }

            do {
                $order->order_number = OrderNumber::generate();
            } while (static::query()->where('order_number', $order->order_number)->exists());
        });
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
