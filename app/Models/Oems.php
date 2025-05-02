<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Oems extends Model
{
    public function scopeOfCode(Builder $query, string $code): Builder
    {
        return $query->where('dt_invoice', '=', $code)->orWhere('dt_oem', '=', $code);
    }
}
