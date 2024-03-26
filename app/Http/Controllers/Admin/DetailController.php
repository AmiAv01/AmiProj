<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Detail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DetailController extends Controller
{
    public function index()
    {
        $details = DB::table('detail')->paginate(10);

        return Inertia::render('Admin/Detail/Index', ['details' => $details->items(), 'pagination' => $details]);
    }

    public function store(Request $request)
    {

    }

    public function update(Request $request, $id)
    {

    }

    public function delete(Request $request, $id)
    {
        //dd($id);
        $detail = Detail::where('dt_id', $id)->delete();

        return redirect()->route('admin.details.index')->with('success', 'Product deleted successfully');
    }
}
