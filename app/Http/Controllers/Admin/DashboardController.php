<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Models\BlogPost;
use App\Models\Contact;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistiques Portfolio
        $totalPortfolio = Portfolio::count();
        $publishedPortfolio = Portfolio::where('is_active', true)->count();
        $newPortfolio = Portfolio::where('created_at', '>=', Carbon::now()->subDays(30))->count();

        // Statistiques BlogPost
        $totalBlogPostPosts = BlogPost::count();
        $publishedPosts = BlogPost::where('is_published', true)->count();
        $newBlogPostPosts = BlogPost::where('created_at', '>=', Carbon::now()->subDays(30))->count();

        // Statistiques Contacts
        $totalContacts = Contact::count();
        $unreadContacts = Contact::where('is_read', false)->count();

        // Statistiques Utilisateurs (corrigé pour Spatie Permission)
        $totalUsers = User::count();

        // Compter les admins via le rôle 'admin' ou 'super-admin'
        $adminRole = Role::whereIn('name', ['admin', 'super-admin'])->first();
        $adminCount = 0;
        if ($adminRole) {
            $adminCount = User::role(['admin', 'super-admin'])->count();
        }

        $newUsers = User::where('created_at', '>=', Carbon::now()->subDays(30))->count();
        $openTickets = Ticket::where('status', 'open')->count();

        // Données pour le graphique d'activité (12 derniers mois)
        $months = [];
        $portfolioMonthly = [];
        $BlogPostMonthly = [];

        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months[] = $month->locale('fr')->isoFormat('MMM');

            $portfolioMonthly[] = Portfolio::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();

            $BlogPostMonthly[] = BlogPost::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }

        // Données pour le graphique des catégories Portfolio
        $categories = Portfolio::select('category', DB::raw('count(*) as total'))
            ->whereNotNull('category')
            ->groupBy('category')
            ->get();

        $categoryLabels = $categories->pluck('category')->toArray();
        $categoryData = $categories->pluck('total')->toArray();

        // Si pas de catégories, valeurs par défaut
        if (empty($categoryLabels)) {
            $categoryLabels = ['Site Vitrine', 'E-commerce', 'Application Web', 'Maintenance', 'Autres'];
            $categoryData = [0, 0, 0, 0, 0];
        }

        // Éléments récents
        $recentPortfolio = Portfolio::latest()->take(5)->get();
        $recentPosts = BlogPost::latest()->take(5)->get();
        $recentContacts = Contact::latest()->take(5)->get();
        $recentUsers = User::latest()->take(5)->get();

        // Statistiques supplémentaires
        $stats = [
            'published_portfolio' => $publishedPortfolio,
            'published_posts' => $publishedPosts,
            'total_users' => $totalUsers,
            'open_tickets' => $openTickets,
        ];

        return view('admin.dashboard.index', [
            'totalPortfolio' => $totalPortfolio,
            'publishedPortfolio' => $publishedPortfolio,
            'newPortfolio' => $newPortfolio,
            'totalBlogPostPosts' => $totalBlogPostPosts,
            'publishedPosts' => $publishedPosts,
            'newBlogPostPosts' => $newBlogPostPosts,
            'totalContacts' => $totalContacts,
            'unreadContacts' => $unreadContacts,
            'totalUsers' => $totalUsers,
            'adminCount' => $adminCount,
            'newUsers' => $newUsers,
            'openTickets' => $openTickets,
            'stats' => $stats,
            'recentPortfolio' => $recentPortfolio,
            'recentPosts' => $recentPosts,
            'recentContacts' => $recentContacts,
            'recentUsers' => $recentUsers,
            'chartLabels' => $months,
            'chartPortfolio' => $portfolioMonthly,
            'chartBlogPost' => $BlogPostMonthly,
            'categoryLabels' => $categoryLabels,
            'categoryData' => $categoryData,
        ]);
    }
}
