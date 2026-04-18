<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\CompanyInfo;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function index()
    {
        $company = CompanyInfo::first();
        
        // Correction : Récupérer TOUS les projets actifs, pas seulement internal
        $portfolios = Portfolio::active()
                              ->ordered()
                              ->paginate(9);

        return view('novatechweb.views.portfolio.index', compact('company', 'portfolios'));
    }

    public function show($slug)
    {
        $company = CompanyInfo::first();
        $portfolio = Portfolio::where('slug', $slug)
                             ->where('is_active', true)
                             ->firstOrFail();

        // Récupérer des projets similaires
        $relatedPortfolios = Portfolio::active()
                                     ->where('category', $portfolio->category)
                                     ->where('id', '!=', $portfolio->id)
                                     ->limit(3)
                                     ->get();

        return view('novatechweb.views.portfolio.show', compact('company', 'portfolio', 'relatedPortfolios'));
    }
}