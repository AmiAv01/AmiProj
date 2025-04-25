<?php

namespace App\Services;

use App\DTO\SearchQueryDTO;
use App\Exceptions\NoResultsFoundException;
use App\Models\Detail;
use App\Models\Oems;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

final class SearchService{

    public function __construct(private OemService $oemService){}
    public function getBySearching(SearchQueryDTO $dto): array
    {
        $detailsFromOems = $this->oemService->findDetailsByQuery($dto->searchQuery);
        if ($detailsFromOems->isEmpty()){
            throw new NoResultsFoundException($dto->searchQuery);
        }
        $processedDetails = $this->processDetails($detailsFromOems, $dto->searchQuery);
        return $this->formatResults($processedDetails);
    }
    public function getBySearchingWithPagination(SearchQueryDTO $dto): LengthAwarePaginator
    {
        $details = $this->getBySearching($dto);
        return new LengthAwarePaginator($details, count($details), 10);
    }

    private function processDetails(Collection $details, string $searchQuery): array{
        return $details->map(function ($detail) use ($searchQuery) {
            return $this->oemService->getInfoAboutDetailFromOems($detail, $searchQuery)->toArray();
        })->toArray();
    }

    private function formatResults(array $details): array{
        usort($details, fn($a, $b) => strcmp($a['dt_code'], $b['dt_code']));
        $result = [];
        foreach ($details as $detail) {
            $result[md5($detail['dt_code'].$detail['dt_firm'])] = $detail;
        }
        return $result;
    }

}

