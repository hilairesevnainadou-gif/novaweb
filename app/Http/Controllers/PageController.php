<?php
// app/Http/Controllers/PageController.php

namespace App\Http\Controllers;

use App\Models\CompanyInfo;
use App\Models\Faq;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\Tool;

class PageController extends Controller
{
    public function mentionsLegales()
    {
        $company = CompanyInfo::first();
        return view('novatechweb.views.mentions-legales', compact('company'));
    }

    public function politiqueConfidentialite()
    {
        $company = CompanyInfo::first();
        return view('novatechweb.views.politique-confidentialite', compact('company'));
    }

    public function services()
{
    $company = CompanyInfo::first();
    $services = Service::active()->ordered()->get();
    $tools = Tool::active()->ordered()->get();
    $faqs = Faq::active()->ordered()->get();
    $testimonials = Testimonial::where('is_active', true)
                               ->orderBy('created_at', 'desc')
                               ->limit(6)
                               ->get();

    return view('novatechweb.views.services', compact('company', 'services', 'tools', 'faqs', 'testimonials'));
}
}
