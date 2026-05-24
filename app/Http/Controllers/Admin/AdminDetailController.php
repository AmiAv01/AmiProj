<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Detail;
use App\Models\Firm;
use App\Services\DetailService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminDetailController extends Controller
{
    public function __construct(protected DetailService $detailService) {}

    public function index(): Response
    {
        return Inertia::render('Admin/Detail/DetailList', ['details' => $this->detailService->getByBrand(12), 'brands' => Firm::all()]);
    }

    public function delete(int $id)
    {
        Detail::where('dt_id', $id)->delete();

        return redirect()->route('admin.details.index')->with('success', 'Product deleted successfully');
    }

    public function store(Request $request): RedirectResponse
    {
        abort(501, 'Detail creation is not implemented yet.');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        abort(501, 'Detail update is not implemented yet.');
    }
}
