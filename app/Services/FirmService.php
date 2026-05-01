<?php

namespace App\Services;

use App\Models\Firm;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class FirmService
{
    public function getAll(): Collection
    {
        return Cache::remember('firms.all', now()->addMinutes(10), static fn (): Collection => Firm::all());
    }
}
