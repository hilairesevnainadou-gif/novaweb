<?php
// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Models\BlogPost;
use App\Models\Contact;
use App\Models\User;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Ticket;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Device;
use App\Models\Intervention;
use App\Models\Tool;
use App\Models\Faq;
use App\Models\Testimonial;
use App\Models\Newsletter;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // ========== STATISTIQUES DE BASE ==========

        // Portfolio
        $totalPortfolio = 0;
        $publishedPortfolio = 0;
        $newPortfolio = 0;
        if ($user->can('portfolio.view')) {
            $totalPortfolio = Portfolio::count();
            $publishedPortfolio = Portfolio::where('is_active', true)->count();
            $newPortfolio = Portfolio::where('created_at', '>=', Carbon::now()->subDays(30))->count();
        }

        // Blog
        $totalBlogPosts = 0;
        $publishedPosts = 0;
        $newBlogPosts = 0;
        if ($user->can('blog.view')) {
            $totalBlogPosts = BlogPost::count();
            $publishedPosts = BlogPost::where('is_published', true)->count();
            $newBlogPosts = BlogPost::where('created_at', '>=', Carbon::now()->subDays(30))->count();
        }

        // Contacts
        $totalContacts = 0;
        $unreadContacts = 0;
        if ($user->can('contact.view')) {
            $totalContacts = Contact::count();
            $unreadContacts = Contact::where('is_read', false)->count();
        }

        // Utilisateurs
        $totalUsers = 0;
        $newUsers = 0;
        $adminCount = 0;
        if ($user->can('users.view')) {
            $totalUsers = User::count();
            $newUsers = User::where('created_at', '>=', Carbon::now()->subDays(30))->count();
            $adminCount = User::role(['super-admin', 'admin'])->count();
        }

        // Tickets
        $openTickets = 0;
        if ($user->can('tickets.view')) {
            $openTickets = Ticket::open()->count();
        }

        // Clients
        $totalClients = 0;
        $newClients = 0;
        if ($user->can('clients.view')) {
            $totalClients = Client::count();
            $newClients = Client::where('created_at', '>=', Carbon::now()->subDays(30))->count();
        }

        // Facturation
        $totalInvoiced = 0;
        $totalPaid = 0;
        $outstandingAmount = 0;
        if ($user->can('billing.view')) {
            $totalInvoiced = Invoice::sum('total');
            $totalPaid = Payment::where('status', 'completed')->sum('amount');
            $outstandingAmount = Invoice::unpaid()->sum('remaining_amount');
        }

        // ========== MAINTENANCE - ADAPTÉ SELON LE RÔLE ==========
        // Seuls les utilisateurs avec permission 'maintenance.view' ET qui peuvent voir toutes les interventions
        // ou qui ne sont pas techniciens voient ces stats
        $totalDevices = 0;
        $devicesInMaintenance = 0;
        $pendingInterventions = 0;
        $completedInterventionsThisMonth = 0;

        $canViewMaintenanceStats = $user->can('maintenance.view') &&
            ($user->can('interventions.view.all') ||
             $user->hasRole('super-admin') ||
             $user->hasRole('admin') ||
             $user->hasRole('support'));

        if ($canViewMaintenanceStats) {
            $totalDevices = Device::count();
            $devicesInMaintenance = Device::whereIn('status', ['maintenance', 'repair'])->count();

            $canViewAllInterventions = $user->can('interventions.view.all') ||
                                       $user->hasRole('super-admin') ||
                                       $user->hasRole('admin') ||
                                       $user->hasRole('support');

            if ($canViewAllInterventions) {
                // Admin, Super Admin, Support : voient toutes les interventions
                $pendingInterventions = Intervention::pending()->count();
                $completedInterventionsThisMonth = Intervention::completed()
                    ->whereMonth('end_date', Carbon::now()->month)
                    ->whereYear('end_date', Carbon::now()->year)
                    ->count();
            } else {
                // Technicien : ne voit que ses propres interventions
                $pendingInterventions = Intervention::where('technician_id', $user->id)
                    ->pending()
                    ->count();
                $completedInterventionsThisMonth = Intervention::where('technician_id', $user->id)
                    ->completed()
                    ->whereMonth('end_date', Carbon::now()->month)
                    ->whereYear('end_date', Carbon::now()->year)
                    ->count();
            }
        }

        // Services
        $totalServices = 0;
        if ($user->can('services.view')) {
            $totalServices = Service::where('is_active', true)->count();
        }

        // Team
        $totalTeamMembers = 0;
        if ($user->can('team.view')) {
            $totalTeamMembers = TeamMember::where('is_active', true)->count();
        }

        // Tools
        $totalTools = 0;
        if ($user->can('tools.view')) {
            $totalTools = Tool::where('is_active', true)->count();
        }

        // FAQ
        $totalFaqs = 0;
        if ($user->can('faqs.view')) {
            $totalFaqs = Faq::where('is_active', true)->count();
        }

        // Testimonials
        $totalTestimonials = 0;
        if ($user->can('testimonials.view')) {
            $totalTestimonials = Testimonial::where('is_active', true)->count();
        }

        // Newsletter
        $newsletterSubscribers = 0;
        if ($user->can('newsletter.view')) {
            $newsletterSubscribers = Newsletter::where('is_active', true)->count();
        }

        // ========== GRAPHIQUES ==========

        // Données pour le graphique d'activité mensuelle (12 derniers mois)
        $months = [];
        $portfolioMonthly = [];
        $blogMonthly = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->locale('fr')->isoFormat('MMM');

            if ($user->can('portfolio.view')) {
                $portfolioMonthly[] = Portfolio::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();
            } else {
                $portfolioMonthly[] = 0;
            }

            if ($user->can('blog.view')) {
                $blogMonthly[] = BlogPost::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();
            } else {
                $blogMonthly[] = 0;
            }
        }

        // Catégories de projets Portfolio
        $categoryLabels = ['Site Vitrine', 'E-commerce', 'Application Web', 'Maintenance', 'Autres'];
        $categoryData = [0, 0, 0, 0, 0];
        if ($user->can('portfolio.view')) {
            $categoryData = [
                Portfolio::where('category', 'site_vitrine')->count(),
                Portfolio::where('category', 'ecommerce')->count(),
                Portfolio::where('category', 'application_web')->count(),
                Portfolio::where('category', 'maintenance')->count(),
                Portfolio::where('category', 'autre')->orWhereNull('category')->count(),
            ];
        }

        // ========== LISTES RÉCENTES ==========

        // Projets récents
        $recentPortfolio = collect();
        if ($user->can('portfolio.view')) {
            $recentPortfolio = Portfolio::latest()->take(5)->get();
        }

        // Articles récents
        $recentPosts = collect();
        if ($user->can('blog.view')) {
            $recentPosts = BlogPost::latest()->take(5)->get();
        }

        // Messages récents
        $recentContacts = collect();
        if ($user->can('contact.view')) {
            $recentContacts = Contact::latest()->take(5)->get();
        }

        // Utilisateurs récents
        $recentUsers = collect();
        if ($user->can('users.view')) {
            $recentUsers = User::latest()->take(5)->get();
        }

        /**
         * Interventions récentes
         * RÈGLE : L'utilisateur ne voit que les interventions où il est le technicien assigné
         * SAUF s'il a la permission 'interventions.view.all' (admin, super-admin, support)
         */
        $recentInterventions = collect();
        if ($user->can('interventions.view')) {
            $query = Intervention::with(['device', 'client', 'technician']);

            // Si l'utilisateur n'a PAS la permission de voir toutes les interventions
            if (!$user->can('interventions.view.all') &&
                !$user->hasRole('super-admin') &&
                !$user->hasRole('admin') &&
                !$user->hasRole('support')) {
                $query->where('technician_id', $user->id);
            }

            $recentInterventions = $query->latest()->take(5)->get();
        }

        // Tickets récents
        $recentTickets = collect();
        if ($user->can('tickets.view')) {
            $recentTickets = Ticket::with(['user', 'assignedTo'])->latest()->take(5)->get();
        }

        // Factures récentes
        $recentInvoices = collect();
        if ($user->can('billing.invoices.view')) {
            $recentInvoices = Invoice::with('client')->latest()->take(5)->get();
        }

        // FAQ récentes
        $recentFaqs = collect();
        if ($user->can('faqs.view')) {
            $recentFaqs = Faq::latest()->take(5)->get();
        }

        // Témoignages récents
        $recentTestimonials = collect();
        if ($user->can('testimonials.view')) {
            $recentTestimonials = Testimonial::latest()->take(5)->get();
        }

        return view('admin.dashboard.index', compact(
            // Stats générales
            'totalPortfolio', 'publishedPortfolio', 'newPortfolio',
            'totalBlogPosts', 'publishedPosts', 'newBlogPosts',
            'totalContacts', 'unreadContacts',
            'totalUsers', 'newUsers', 'adminCount',
            'openTickets',
            'totalClients', 'newClients',
            'totalInvoiced', 'totalPaid', 'outstandingAmount',

            // Maintenance (adapté - peut être vide pour technicien)
            'totalDevices', 'devicesInMaintenance', 'pendingInterventions', 'completedInterventionsThisMonth',

            // Autres stats
            'totalServices', 'totalTeamMembers', 'totalTools', 'totalFaqs', 'totalTestimonials', 'newsletterSubscribers',

            // Graphiques
            'months', 'portfolioMonthly', 'blogMonthly',
            'categoryLabels', 'categoryData',

            // Listes récentes
            'recentPortfolio', 'recentPosts', 'recentContacts', 'recentUsers',
            'recentInterventions', 'recentTickets', 'recentInvoices',
            'recentFaqs', 'recentTestimonials'
        ));
    }
}
