<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Detail extends Model
{
    use HasFactory;

    public $table = 'detail';
    protected $primaryKey = 'dt_id';
    protected $fillable = [
        'dt_id',
        'dt_code',
        'dt_extcode',
        'dt_extname',
        'dt_type',
        'dt_comment',
        'dt_foto',
        'dt_invoice',
        'dt_netto',
        'dt_oem',
        'dt_baza',
        'dt_cena',
        'dt_prod',
        'dt_ost',
        'dt_ostc',
        'dt_typec',
        'dt_bp',
        'dt_cargo',
        'dt_e',
        'dt_hs',
        'dt_datep',
        'dt_name',
        'fr_code',
        'dt_tp_ptype',
        'lt_dt_acode',
    ];

    public function scopeInvoice(Builder $query, string $invoiceCode): void
    {
        $query->where('dt_invoice', '=', $invoiceCode);
    }

    public function orderItem(): hasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
