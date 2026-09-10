<?php

namespace App\Services;

use App\DTO\SearchQueryDTO;
use App\Exceptions\NoResultsFoundException;
use App\Models\Detail;
use App\Services\Product\ProductImageService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

final class SearchService
{
    private const int RESULTS_PER_PAGE = 10;

    public function __construct(
        private readonly OemService $oemService,
        private readonly ProductImageService $imageService,
    ) {}

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
        $perPage = self::RESULTS_PER_PAGE;
        $paginator = $this->oemService->buildUniqueDetailsQuery($dto->searchQuery)->paginate($perPage);
        if ($paginator->isEmpty()) {
            throw new NoResultsFoundException($dto->searchQuery);
        }
        $processedDetails = $this->processDetails(collect($paginator->items()), $dto->searchQuery);
        $details = array_values($this->formatResults($processedDetails));
        $paginator->setCollection(collect($details));
        $paginator->withQueryString();

        return $paginator;
    }

    private function processDetails(Collection $details, string $searchQuery): array
    {
        $photosByInvoice = Detail::query()
            ->whereIn('dt_invoice', $details->pluck('dt_invoice')->filter()->unique())
            ->pluck('dt_foto', 'dt_invoice');

        return $details->map(function ($detail) use ($searchQuery, $photosByInvoice) {
            $result = $this->oemService->getInfoAboutDetailFromOems($detail, $searchQuery)->toArray();
            $result['imageUrl'] = $this->imageService->getImageUrl($photosByInvoice->get($detail['dt_invoice']));

            return $result;
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
