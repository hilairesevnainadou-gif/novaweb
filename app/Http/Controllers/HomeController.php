<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\CompanyInfo;
use App\Models\Faq;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\Tool;

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
        $tools = Tool::active()->ordered()->get();
        $faqs = Faq::active()->ordered()->get();

        // Récupérer les projets actifs
        $portfolios = Portfolio::active()
                              ->ordered()
                              ->limit(6)
                              ->get();

        // Si aucun projet actif, récupérer les projets les plus récents
        if ($portfolios->isEmpty()) {
            $portfolios = Portfolio::ordered()->limit(6)->get();
        }

        return view('novatechweb.views.welcome', compact('company', 'services', 'testimonials', 'portfolios', 'tools', 'faqs'));
    }

    public function about()
{
    $company = CompanyInfo::first();
    $tools = Tool::active()->ordered()->get();
    $testimonials = Testimonial::where('is_active', true)->orderBy('created_at', 'desc')->limit(6)->get();
    $team = TeamMember::where('is_active', true)->ordered()->get(); // Si vous avez un modèle Team

    // Statistiques
    $projectsCount = Portfolio::active()->count();
    $clientsCount = Client::count(); // ou Testimonial::distinct('client_id')->count()
    $teamCount = TeamMember::where('is_active', true)->count();

    return view('novatechweb.views.about', compact('company', 'tools', 'testimonials', 'team', 'projectsCount', 'clientsCount', 'teamCount'));
}
}
