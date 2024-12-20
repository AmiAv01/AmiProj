<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Detail;
use App\Models\Firm;
use App\Services\DetailService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminDetailController extends Controller
{
    public function __construct(protected DetailService $detailService)
    {

    }

    public function index()
    {
        return Inertia::render('Admin/Detail/DetailList', ['details' => $this->detailService->getByBrand(12), 'brands' => Firm::all()]);
    }

    public function delete(Request $request, $id)
    {
        Detail::where('dt_id', $id)->delete();

        return redirect()->route('admin.details.index')->with('success', 'Product deleted successfully');
    }
}
