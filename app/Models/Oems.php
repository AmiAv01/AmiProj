<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Oems extends Model
{
    use HasFactory, Searchable;

    public function toSearchableArray()
    {
        return [
            'dt_invoice' => $this->dt_invoice,
            'dt_oem' => $this->dt_oem,
        ];
    }
}
