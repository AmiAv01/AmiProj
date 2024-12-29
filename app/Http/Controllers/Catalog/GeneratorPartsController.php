<?php

namespace App\Http\Controllers\Catalog;

use App\DTO\FilterDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\DetailsFilterRequest;
use App\Services\DetailService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class GeneratorPartsController extends Controller
{
    private array $categories = ['sleeve' => ['ВТУЛКА ГЕНЕРАТОРА', 'ВТУЛКА ПОДШИПНИКА'], 'pump' => ['ВАКУУМНЫЙ НАСОС ГЕНЕРАТОРА', 'ЛОПАСТИ ВАКУУМНОГО НАСОСА'],
        'rectifier' => ['ВЫПРЯМИТЕЛЬ ГЕНЕРАТОРА', 'ВЫПРЯМИТЕЛЬ + КРЫШКА ГЕНЕРАТОРА', 'ВЫПРЯМИТЕЛЬ + РЕГУЛЯТОР ГЕНЕРАТОРА'], 'diode' => ['ДИОД', 'ТРИОД ГЕНЕРАТОРА'],
        'insulator' => 'ИЗОЛЯТОР ГЕНЕРАТОРА', 'cover' => ['КРЫШКА ГЕНЕРАТОРА', 'КРЫШКА ГЕНЕРАТОРА ЗАДНЯЯ', 'КРЫШКА ГЕНЕРАТОРА ПЕРЕДНЯЯ', 'КРЫШКА ГЕНЕРАТОРА ЗАЩИТНАЯ'],
        'regulator' => 'РЕГУЛЯТОР НАПРЯЖЕНИЯ ГЕНЕРАТОРА', 'rotor' => ['РОТОР ГЕНЕРАТОРА', 'КОЛЛЕКТОР РОТОРА ГЕНЕРАТОРА'],
        'terminal' => 'ТЕРМИНАЛ', 'gland' => 'САЛЬНИК ГЕНЕРАТОРА', 'winding' => 'СТАТОРНАЯ ОБМОТКА ГЕНЕРАТОРА',
        'pulley' => ['ШКИВ ГЕНЕРАТОРА', 'ШКИВ ГЕНЕРАТОРА ОБГОННЫЙ', 'КРЫШКА ШКИВА', 'ПРИВОДНОЙ РЕМЕНЬ ШКИВА'], 'brush' => 'ЩЁТКИ ГЕНЕРАТОРА', 'holder' => 'ЩЁТОЧНЫЙ УЗЕЛ ГЕНЕРАТОРА'];

    private array $names = ['sleeve' => 'Втулки генератора', 'pump' => 'Вакуумные насосы', 'rectifier' => 'Выпрямители', 'diode' => 'Диоды и триоды',
        'insulator' => 'Изоляторы', 'cover' => 'Крышки', 'regulator' => 'Регуляторы', 'rotor' => 'Роторы', 'terminal' => 'Терминалы',
        'gland' => 'Сальники', 'winding' => 'Статорные обмотки', 'pulley' => 'Шкивы', 'brush' => 'Щётки генератора', 'holder' => 'Щёточные узлы'];

    public function __construct(protected DetailService $detailService)
    {
    }

    public function index(string $category, DetailsFilterRequest $request): Response
    {
        $details = $this->detailService->getByFilters(is_array($this->categories[$category]) ? $this->categories[$category] : [$this->categories[$category]], 12);

        return Inertia::render('Catalog/Index', [
            'details' => $details,
            'title' => $this->names[$category],
            'clientBrands' => $this->detailService->getClientBrands(new FilterDTO($request->validated('filter'))),
        ]);
    }
}
