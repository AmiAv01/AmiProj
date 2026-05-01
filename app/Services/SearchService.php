<?php

namespace App\Services;

use App\DTO\SearchQueryDTO;
use App\Exceptions\NoResultsFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

final class SearchService
{
    public function __construct(private OemService $oemService) {}

    public function getBySearching(SearchQueryDTO $dto): array
    {
        $detailsFromOems = $this->oemService->findDetailsByQuery($dto->searchQuery);
        if ($detailsFromOems->isEmpty()) {
            throw new NoResultsFoundException($dto->searchQuery);
        }
        $processedDetails = $this->processDetails($detailsFromOems, $dto->searchQuery);

        return $this->formatResults($processedDetails);
    }

    public function getBySearchingWithPagination(SearchQueryDTO $dto): LengthAwarePaginator
    {
        $page = Paginator::resolveCurrentPage();
        $perPage = 10;
        $paginator = $this->oemService->buildDetailsQuery($dto->searchQuery)->paginate($perPage, ['*'], 'page', $page);
        if ($paginator->isEmpty()) {
            throw new NoResultsFoundException($dto->searchQuery);
        }
        $processedDetails = $this->processDetails(collect($paginator->items()), $dto->searchQuery);
        $details = array_values($this->formatResults($processedDetails));

        return new LengthAwarePaginator($details, $paginator->total(), $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);
    }

    private function processDetails(Collection $details, string $searchQuery): array
    {
        return $details->map(function ($detail) use ($searchQuery) {
            return $this->oemService->getInfoAboutDetailFromOems($detail, $searchQuery)->toArray();
        })->toArray();
    }

    private function formatResults(array $details): array
    {
        usort($details, fn ($a, $b) => strcmp($a['dt_code'], $b['dt_code']));
        $result = [];
        foreach ($details as $detail) {
            $result[md5($detail['dt_code'].$detail['dt_firm'])] = $detail;
        }

        return $result;
    }
}
