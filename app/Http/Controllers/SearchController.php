<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SearchController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $query = $request->input('query');
        $materialResults = [];
        $featureResults = [];

        // Cari di material.search API
        $materialResponse = Http::post(config('services.material_search_url'), [
            'query' => $query
        ]);

        if ($materialResponse->ok()) {
            $materialResults = $materialResponse->json();
        }

        // Jika tidak ada hasil dari material.search, lanjut ke feature.search API
        if (empty($materialResults)) {
            $featureResponse = Http::post(config('services.feature_search_url'), [
                'query' => $query
            ]);

            if ($featureResponse->ok()) {
                $featureResults = $featureResponse->json();
            }
        }

        // Gabungkan hasil dari kedua pencarian
        $results = array_merge($materialResults, $featureResults);

        return response()->json($results);
    }
}
