<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RozCz extends Model
{
    protected $table = 'roz_cz';

    protected $casts = [
        'datep' => 'datetime',
    ];
}
