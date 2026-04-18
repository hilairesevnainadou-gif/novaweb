<?php
// app/Http/Controllers/Api/AdminStatsController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Portfolio;
use App\Models\BlogPost;
use App\Models\Contact;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminStatsController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'month');

        $stats = [
            'total_users' => User::count(),
            'total_portfolio' => Portfolio::count(),
            'total_blog_posts' => BlogPost::count(),
            'total_contacts' => Contact::count(),
            'unread_contacts' => Contact::where('is_read', false)->count(),
            'open_tickets' => Ticket::where('status', 'open')->count(),
        ];

        // Statistiques par période
        switch ($period) {
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                break;
            case 'year':
                $startDate = Carbon::now()->startOfYear();
                break;
            default:
                $startDate = Carbon::now()->subDays(30);
        }

        $stats['new_users'] = User::where('created_at', '>=', $startDate)->count();
        $stats['new_portfolio'] = Portfolio::where('created_at', '>=', $startDate)->count();
        $stats['new_blog_posts'] = BlogPost::where('created_at', '>=', $startDate)->count();
        $stats['new_contacts'] = Contact::where('created_at', '>=', $startDate)->count();

        // Graphique données
        $stats['chart'] = $this->getChartData($period);

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    private function getChartData($period)
    {
        $data = [];

        switch ($period) {
            case 'week':
                for ($i = 6; $i >= 0; $i--) {
                    $date = Carbon::now()->subDays($i);
                    $data['labels'][] = $date->format('D');
                    $data['portfolio'][] = Portfolio::whereDate('created_at', $date)->count();
                    $data['blog'][] = BlogPost::whereDate('created_at', $date)->count();
                }
                break;
            case 'month':
                $daysInMonth = Carbon::now()->daysInMonth;
                for ($i = 1; $i <= $daysInMonth; $i++) {
                    $date = Carbon::now()->day($i);
                    $data['labels'][] = $i;
                    $data['portfolio'][] = Portfolio::whereDate('created_at', $date)->count();
                    $data['blog'][] = BlogPost::whereDate('created_at', $date)->count();
                }
                break;
            case 'year':
                for ($i = 1; $i <= 12; $i++) {
                    $data['labels'][] = Carbon::create()->month($i)->format('M');
                    $data['portfolio'][] = Portfolio::whereMonth('created_at', $i)->count();
                    $data['blog'][] = BlogPost::whereMonth('created_at', $i)->count();
                }
                break;
        }

        return $data;
    }
}
