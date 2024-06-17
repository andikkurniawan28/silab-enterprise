<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use Illuminate\Http\Request;

class FeatureSearchController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $query = $request->input('query');

        // Query untuk pencarian fitur berdasarkan nama atau deskripsi
        $features = Feature::where('name', 'like', "%$query%")
                           ->orWhere('description', 'like', "%$query%")
                           ->limit(10) // Batasi jumlah hasil maksimal
                           ->get();

        return response()->json($features);
    }
}
