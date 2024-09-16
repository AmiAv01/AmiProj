<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AltCz extends Model
{
    protected $table = 'alt_cz';

    protected $casts = [
        'datep' => 'datetime',
    ];
}
