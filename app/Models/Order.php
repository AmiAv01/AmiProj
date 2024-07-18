<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public $table = 'order';

    protected $fillable = [
        'total_price',
        'status',
        'session_id',
        'created_by',
        'updated_by',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
