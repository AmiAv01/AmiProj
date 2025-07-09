<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    public $table = 'order_item';

    protected $fillable = [
        'detail_id',
        'quantity',
        'unit_price',
    ];

    public function detail(): BelongsTo
    {
        return $this->belongsTo(Detail::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
