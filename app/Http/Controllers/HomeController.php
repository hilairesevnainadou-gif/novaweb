<?php

namespace App\Http\Controllers;

use App\Models\CompanyInfo;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\Testimonial;

class HomeController extends Controller
{
    public function index()
    {
        $company = CompanyInfo::first();
        $services = Service::active()->ordered()->get();

        // Récupérer les témoignages actifs - tri simple sans 'order'
        $testimonials = Testimonial::where('is_active', true)
                                   ->orderBy('created_at', 'desc')
                                   ->limit(6)
                                   ->get();

        // Récupérer les projets actifs
        $portfolios = Portfolio::active()
                              ->ordered()
                              ->limit(6)
                              ->get();

        // Si aucun projet actif, récupérer les projets les plus récents
        if ($portfolios->isEmpty()) {
            $portfolios = Portfolio::ordered()->limit(6)->get();
        }

        return view('novatechweb.views.welcome', compact('company', 'services', 'testimonials', 'portfolios'));
    }
}
