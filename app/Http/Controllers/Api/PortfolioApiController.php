<?php
// app/Http/Controllers/Api/PortfolioApiController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use Illuminate\Http\Request;

class PortfolioApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Portfolio::active()->ordered();

        // Filtre par catégorie
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // Filtre par type de projet
        if ($request->has('project_type')) {
            $query->where('project_type', $request->project_type);
        }

        // Limite
        $limit = $request->get('limit', 10);

        $portfolios = $query->paginate($limit);

        return response()->json([
            'success' => true,
            'data' => $portfolios
        ]);
    }

    public function show($slug)
    {
        $portfolio = Portfolio::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $portfolio
        ]);
    }
}
