<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;

    public $table = 'cart_item';

    protected $fillable = ['cart_id', 'dt_id', 'quantity', 'price'];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function detail(): BelongsTo
    {
        return $this->belongsTo(Detail::class, 'dt_id', 'dt_id');
    }
}
