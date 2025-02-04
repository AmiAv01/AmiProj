<?php

namespace App\Services;

use App\Models\Firm;
use Illuminate\Database\Eloquent\Collection;

class FirmService
{
    public function getAll(): Collection{
        return Firm::all();
    }
}
