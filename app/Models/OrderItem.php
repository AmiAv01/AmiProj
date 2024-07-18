<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    public $table = 'order_item';

    protected $fillable = [
        'detail_id',
        'quantity',
        'unit_price',
    ];

    public function getDetail()
    {
        return $this->belongsTo(Detail::class);
    }
}
