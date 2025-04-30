<?php

namespace App\Services;

use App\DTO\FilterDTO;
use App\Exceptions\DetailNotFoundException;
use App\Exceptions\InvalidInvoiceException;
use App\Models\Detail;
use App\Models\Firm;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Pagination\LengthAwarePaginator;
final class DetailService
{
    public function getAll(int $perPage): LengthAwarePaginator
    {
        return Detail::select(['dt_id', 'dt_invoice', 'dt_typec', 'dt_cargo', 'fr_code', 'dt_oem'])->paginate($perPage);
    }

    public function getByFilters(array $categories, int $perPage): LengthAwarePaginator
    {
        $brands = QueryBuilder::for(Firm::class)->allowedFilters(AllowedFilter::exact('id', 'fr_code', true, null))->get();
        return Detail::whereIn('dt_typec', $categories)->whereIn('fr_code', $brands->pluck('fr_name')->toArray())
            ->select(['dt_id', 'dt_invoice', 'dt_typec', 'dt_cargo', 'fr_code', 'dt_oem'])->paginate($perPage)->withQueryString();
    }

    public function getByBrand(int $perPage): LengthAwarePaginator
    {
        $brands = QueryBuilder::for(Firm::class)->allowedFilters(AllowedFilter::exact('id', 'fr_code'))->get();

        return Detail::whereIn('fr_code', $brands->pluck('fr_name')->toArray())
            ->join('stk', 'stk.code', '=', 'detail.dt_code')
            ->select(['dt_id', 'dt_invoice', 'dt_type','dt_cargo', 'fr_code', 'ostc'])->paginate($perPage)->withQueryString();
    }

    public function getByInvoice(string $invoice): Detail|null
    {
        if (empty($invoice)){
            throw new InvalidInvoiceException($invoice);
        }
        $detail = Detail::invoice($invoice)->join('stk', 'stk.code', '=', 'detail.dt_code')
            ->select(['dt_id', 'dt_code', 'dt_foto', 'dt_oem', 'dt_typec', 'dt_invoice', 'dt_cargo', 'fr_code', 'dt_comment', 'ostc'])->first();
        if (!$detail){
            throw new DetailNotFoundException($invoice);
        }
        return $detail;
    }

    public function getClientBrands(FilterDTO $dto): array | null
    {
        return $dto->filter ?: null;
    }


    public function getBySearching(string $search, int $perPage):LengthAwarePaginator
    {
        return Detail::where('dt_invoice', 'like', "%$search%")->orWhere('dt_oem', 'like', "%$search%")
            ->orWhere('dt_cargo', 'like', "%$search%")->orWhere('dt_typec', '=', "$search")
            ->join('stk', 'stk.code', '=', 'detail.dt_code')
            ->select(['dt_id', 'dt_invoice', 'dt_type','dt_cargo', 'fr_code', 'ostc'])->paginate($perPage)->withQueryString();
    }
}
