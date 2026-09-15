<?php

namespace App\Services;

use App\DTO\OemInfoDTO;
use App\Exceptions\InvalidOemCodeException;
use App\Exceptions\OemNotFoundException;
use App\Models\Detail;
use App\Models\Oems;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class OemService
{
    public function getProductInfoFromOems(string $code): OemInfoDTO
    {
        if (empty($code)) {
            throw new InvalidOemCodeException;
        }

        $detailFromOems = Oems::ofCode($code)->first();
        if (! $detailFromOems) {
            throw new OemNotFoundException($code);
        }

        return $this->getInfoAboutDetailFromOems($detailFromOems, $code);
    }

    public function getInfoAboutDetailFromOems(array|Oems $detail, string $searchQuery): OemInfoDTO
    {
        $isStartWithOem = str_starts_with(
            mb_strtolower($detail['dt_oem'], 'UTF-8'),
            mb_strtolower($searchQuery, 'UTF-8'),
        );

        return new OemInfoDTO(
            code: ($isStartWithOem) ? $detail['dt_oem'] : $detail['dt_invoice'],
            firm: ($isStartWithOem) ? $detail['fr_code'] : $detail['dt_parent'],
            type: $detail['dt_typec']
        );
    }

    public function findDetailsByQuery(string $searchQuery): Collection
    {
        return $this->buildDetailsQuery($searchQuery)->get();
    }

    public function buildDetailsQuery(string $searchQuery): EloquentBuilder
    {
        return Oems::query()
            ->where(function (EloquentBuilder $query) use ($searchQuery): void {
                $query->where('dt_invoice', 'like', "$searchQuery%")
                    ->orWhere('dt_oem', 'like', "$searchQuery%");
            });
    }

    public function buildUniqueDetailsQuery(string $searchQuery): EloquentBuilder
    {
        $pattern = "$searchQuery%";
        $codeExpression = 'CASE WHEN dt_oem LIKE ? THEN dt_oem ELSE dt_invoice END';
        $firmExpression = 'CASE WHEN dt_oem LIKE ? THEN fr_code ELSE dt_parent END';
        $representativeIds = $this->buildDetailsQuery($searchQuery)
            ->selectRaw('MIN(id) AS id')
            ->groupByRaw($codeExpression, [$pattern])
            ->groupByRaw($firmExpression, [$pattern]);

        return Oems::query()
            ->whereIn('id', $representativeIds)
            ->orderByRaw($codeExpression, [$pattern])
            ->orderByRaw($firmExpression, [$pattern])
            ->orderBy('id');
    }

    public function findUniqueSearchResults(string $searchQuery): Collection
    {
        return $this->buildUniqueSearchResultsQuery($searchQuery)->get();
    }

    public function buildUniqueSearchResultsQuery(string $searchQuery): QueryBuilder
    {
        $pattern = "$searchQuery%";
        $codeExpression = 'CASE WHEN dt_oem LIKE ? THEN dt_oem ELSE dt_invoice END';
        $firmExpression = 'CASE WHEN dt_oem LIKE ? THEN fr_code ELSE dt_parent END';

        $oemResults = $this->buildDetailsQuery($searchQuery)
            ->selectRaw("{$codeExpression} AS dt_code", [$pattern])
            ->selectRaw("{$firmExpression} AS dt_firm", [$pattern])
            ->addSelect('dt_typec')
            ->selectRaw('dt_invoice AS image_invoice')
            ->toBase();

        // ASS.DBF writes catalog products to `detail`, while OEMS_OUT.DBF writes
        // cross-reference aliases to `oems`. Searching both sources makes a newly
        // imported catalog product discoverable even when it has no OEM row yet.
        $detailResults = Detail::query()
            ->where(function (EloquentBuilder $query) use ($searchQuery): void {
                $query->where('dt_invoice', 'like', "$searchQuery%")
                    ->orWhere('dt_oem', 'like', "$searchQuery%");
            })
            ->where('dt_invoice', '<>', '')
            ->selectRaw('dt_invoice AS dt_code')
            ->selectRaw('fr_code AS dt_firm')
            ->addSelect('dt_typec')
            ->selectRaw('dt_invoice AS image_invoice')
            ->toBase();

        $combinedResults = $oemResults->unionAll($detailResults);

        return DB::query()
            ->fromSub($combinedResults, 'search_results')
            ->select(['dt_code', 'dt_firm'])
            ->selectRaw('MAX(dt_typec) AS dt_typec')
            ->selectRaw('MAX(image_invoice) AS image_invoice')
            ->groupBy('dt_code', 'dt_firm')
            ->orderBy('dt_code')
            ->orderBy('dt_firm');
    }
}
