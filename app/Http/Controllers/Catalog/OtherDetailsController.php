<?php

namespace App\Http\Controllers\Catalog;

use App\DTO\FilterDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\DetailsFilterRequest;
use App\Services\DetailService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OtherDetailsController extends Controller
{
    public function __construct(protected DetailService $detailService)
    {

    }

    public function index(DetailsFilterRequest $request): Response
    {
        $details = $this->detailService->getByFilters(['АВТОХИМИЯ', 'ВИНТ', 'ВЕНТИЛЯТОР САЛОНА', 'ВТУЛКА СФЕРИЧЕСКАЯ', 'ИЗОЛЯТОР', 'ИНСТРУМЕНТ',
            'КРЫШКА ПОДШИПНИКА', 'МУФТА РЕЗИНОВАЯ', 'ОБОРУДОВАНИЕ И ОСНАСТКА', 'ПРИПОЙ ДЛЯ КОНТАКТНОЙ СВАРКИ', 'РЕМКОМПЛЕКТ', 'СМАЗКА ДЛЯ БЕНДИКСОВ MOLYKOTE 33',
            'СТОПОР', 'СТОПОРНАЯ ПЛАСТИНА ПОДШИПНИКА', 'СТОПОРНОЕ КОЛЬЦО', 'СТОПОРНОЕ КОЛЬЦО КОМПЛЕКТ', 'ШАЙБА', 'ШАЙБА ДИСТАНЦИОННАЯ', 'ШПОНКА'], 12);

        return Inertia::render('Catalog/Index', [
            'details' => $details,
            'title' => 'Прочие запчасти',
            'clientBrands' => $this->detailService->getClientBrands(new FilterDTO($request->validated('filter'))),
        ]);
    }
}
