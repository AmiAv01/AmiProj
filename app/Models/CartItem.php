<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    public $table = 'cart_item';
    protected $fillable = ['cart_id', 'dt_id', 'quantity', 'price'];
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product(){
        return $this->belongsTo(Detail::class, 'dt_id', 'dt_id');
    }
}
