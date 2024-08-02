<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Oems extends Model
{
    use HasFactory;

    public static function scopeSearch($query, $keywords)
    {
        return $query->when($keywords, function ($query, $keywords) {
            $query->selectRaw('*, match(dt_invoice, dt_oem, dt_parent, fr_code) against(? IN boolean mode)', [$keywords])->whereRaw('match(dt_invoice, dt_oem, dt_parent, fr_code) against (? IN boolean mode)', [$keywords]);
        }, function ($query) {
            $query->latest();
        });
    }
}
