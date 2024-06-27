<?php

namespace App\Http\Controllers;

use App\Models\Detail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('searchQ');
        $details = Detail::where('dt_invoice', 'like', "%$search%")->orWhere('dt_cargo', 'like', "%$search%")->get();
        Log::channel('error')->info($details);

        return [
            'details' => $details,
        ];
    }
}
