<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Services\DetailService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StarterPartsController extends Controller
{
    private array $categories = ['fork' => 'ВИЛКА СТАРТЕРА', 'sleeve' => ['ВТУЛКА СТАРТЕРА', 'ВТУЛКА СТАРТЕРА КОМПЛЕКТ', 'НАБОР ВТУЛОК СТАРТЕРА'],
        'relay' => 'ВТЯГИВАЮЩЕЕ РЕЛЕ СТАРТЕРА', 'relay_parts' => 'СЕРДЕЧНИК', 'unit_parts' => 'ШЕСТЕРНЯ ПРИВОДА СТАРТЕРА',
        'cover' => 'КРЫШКА СТАРТЕРА', 'unit' => ['ПРИВОД СТАРТЕРА', 'РОЛИК ПРИВОДА СТАРТЕРА', 'ШТОК ПРИВОДА СТАРТЕРА'],
        'other' => ['ДЕРЖАТЕЛЬ ВИЛКИ', 'РЕДУКТОР+ПРИВОД', 'КОНТАКТ(ШИНА) СТАРТЕРА', 'ЗАЩИТНЫЙ КОЛПАЧЁК СТАРТЕРА', 'ВИНТ СТАРТЕРА', 'ПРОВОД СТАРТЕРА', 'МУФТА СТАРТЕРА'],
        'reducer' => ['ВАЛ РЕДУКТОРА СТАРТЕРА', 'ПЛАНЕТАРНЫЙ РЕДУКТОР', 'РЕДУКТОР СТАРТЕРА', 'КРЫШКА РЕДУКТОРА', 'КОРПУС РЕДУКТОРА'], 'repair' => 'РЕМКОМПЛЕКТ СТАРТЕРА',
        'glands' => 'САЛЬНИК СТАРТЕРА', 'stator' => ['СТАТОР', 'КОРПУС СТАТОРА', 'СТАТОР В СБОРЕ С КРЫШКОЙ', 'СТАТОРНАЯ ОБМОТКА СТАРТЕРА'], 'brush' => 'ЩЁТКИ СТАРТЕРА',
        'holder' => 'ЩЁТОЧНЫЙ УЗЕЛ СТАРТЕРА', 'anchor' => ['ЯКОРЬ СТАРТЕРА', 'КРЫШКА ЯКОРЯ']];

    private array $names = ['fork' => 'Вилки стартера', 'sleeve' => 'Втулки стартера', 'relay' => 'Втягивающие реле', 'relay_parts' => 'Детали втягивающего реле',
        'unit_parts' => 'Детали привода стартера', 'cover' => 'Крышки и головы', 'unit' => 'Приводы', 'other' => 'Прочее для стартера', 'reducer' => 'Редукторы и компоненты',
        'repair' => 'Ремкомплекты', 'glands' => 'Сальники стартера', 'stator' => 'Статоры', 'brush' => 'Щётки стартера', 'holder' => 'Щёткодержатели стартера',
        'anchor' => 'Якори стартера'];

    public function __construct(protected DetailService $detailService)
    {
    }

    public function index(string $category, Request $request): Response
    {
        $request->validate([
            'filter' => 'array',
        ]);
        if (! array_key_exists($category, $this->categories)) {
            return abort(404);
        }
        $details = $this->detailService->getByFilters(is_array($this->categories[$category]) ? $this->categories[$category] : [$this->categories[$category]], []);

        return Inertia::render('Catalog/Index', [
            'details' => $details,
            'title' => $this->names[$category],
            'clientBrands' => ($request->query('filter')) ? $request->query('filter') : null,
        ]);
    }
}
