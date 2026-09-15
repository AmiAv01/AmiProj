<?php

namespace App\Services;

use App\DTO\SearchQueryDTO;
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
        $details = $this->oemService->findUniqueSearchResults($dto->searchQuery);
        $processedDetails = $this->processDetails($details);

        return $this->formatResults($processedDetails);
    }

    public function getBySearchingWithPagination(SearchQueryDTO $dto): LengthAwarePaginator
    {
        $perPage = self::RESULTS_PER_PAGE;
        $paginator = $this->oemService->buildUniqueSearchResultsQuery($dto->searchQuery)->paginate($perPage);
        $processedDetails = $this->processDetails(collect($paginator->items()));
        $details = array_values($this->formatResults($processedDetails));
        $paginator->setCollection(collect($details));
        $paginator->withQueryString();

        return $paginator;
    }

    private function processDetails(Collection $details): array
    {
        $photosByInvoice = Detail::query()
            ->whereIn('dt_invoice', $details->pluck('image_invoice')->filter()->unique())
            ->pluck('dt_foto', 'dt_invoice');

        return $details->map(function (mixed $detail) use ($photosByInvoice): array {
            $code = (string) data_get($detail, 'dt_code');
            $firm = (string) data_get($detail, 'dt_firm');
            $type = (string) data_get($detail, 'dt_typec');
            $imageInvoice = (string) data_get($detail, 'image_invoice');
            $photo = $photosByInvoice->get($imageInvoice);

            return [
                'dt_code' => $code,
                'dt_firm' => $firm,
                'dt_typec' => $type,
                'imageUrl' => $this->imageService->getImageUrl(is_string($photo) ? $photo : null),
            ];
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
