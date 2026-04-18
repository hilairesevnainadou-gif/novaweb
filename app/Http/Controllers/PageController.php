<?php
// app/Http/Controllers/PageController.php

namespace App\Http\Controllers;

use App\Models\CompanyInfo;

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
}
