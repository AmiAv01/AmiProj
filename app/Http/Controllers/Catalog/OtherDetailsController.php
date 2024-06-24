<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Models\Firm;
use App\Services\Detail\DetailService;
use Inertia\Inertia;

class OtherDetailsController extends Controller
{
    public function __construct(protected DetailService $detailService)
    {

    }

    public function index()
    {
        $details = $this->detailService->getByCategory(['АВТОХИМИЯ', 'ВИНТ', 'ВЕНТИЛЯТОР САЛОНА', 'ВТУЛКА СФЕРИЧЕСКАЯ', 'ИЗОЛЯТОР', 'ИНСТРУМЕНТ',
            'КРЫШКА ПОДШИПНИКА', 'МУФТА РЕЗИНОВАЯ', 'ОБОРУДОВАНИЕ И ОСНАСТКА', 'ПРИПОЙ ДЛЯ КОНТАКТНОЙ СВАРКИ', 'РЕМКОМПЛЕКТ', 'СМАЗКА ДЛЯ БЕНДИКСОВ MOLYKOTE 33',
            'СТОПОР', 'СТОПОРНАЯ ПЛАСТИНА ПОДШИПНИКА', 'СТОПОРНОЕ КОЛЬЦО', 'СТОПОРНОЕ КОЛЬЦО КОМПЛЕКТ', 'ШАЙБА', 'ШАЙБА ДИСТАНЦИОННАЯ', 'ШПОНКА']);
        $categories = Firm::all();

        return Inertia::render('Catalog/Index', [
            'details' => $details,
            'categories' => $categories,
            'title' => 'Прочие запчасти',
        ]);
    }
}
