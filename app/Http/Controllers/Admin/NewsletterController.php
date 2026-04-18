<?php
// app/Http/Controllers/Admin/NewsletterController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    /**
     * Afficher la liste des abonnés à la newsletter
     */
    public function index()
    {
        $subscribers = Newsletter::latest()->paginate(15);
        $activeCount = Newsletter::where('is_active', true)->count();
        $inactiveCount = Newsletter::where('is_active', false)->count();
        $totalCount = Newsletter::count();

        return view('admin.newsletter.index', compact('subscribers', 'activeCount', 'inactiveCount', 'totalCount'));
    }

    /**
     * Désactiver un abonné (désabonnement)
     */
    public function unsubscribe(Newsletter $newsletter)
    {
        $newsletter->update([
            'is_active' => false,
            'unsubscribed_at' => now()
        ]);

        return redirect()->route('admin.newsletter.index')
            ->with('success', 'L\'abonné a été désabonné avec succès.');
    }

    /**
     * Réactiver un abonné
     */
    public function resubscribe(Newsletter $newsletter)
    {
        $newsletter->update([
            'is_active' => true,
            'unsubscribed_at' => null
        ]);

        return redirect()->route('admin.newsletter.index')
            ->with('success', 'L\'abonné a été réactivé avec succès.');
    }

    /**
     * Supprimer un abonné
     */
    public function destroy(Newsletter $newsletter)
    {
        $newsletter->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.newsletter.index')
            ->with('success', 'L\'abonné a été supprimé avec succès.');
    }

    /**
     * Exporter les abonnés (CSV)
     */
    public function export()
    {
        $subscribers = Newsletter::where('is_active', true)->get();

        $filename = 'abonnes_newsletter_' . date('Y-m-d') . '.csv';

        $handle = fopen('php://output', 'w');

        // En-têtes CSV
        fputcsv($handle, ['ID', 'Email', 'IP Address', 'Date d\'abonnement', 'Statut', 'Date de désabonnement']);

        foreach ($subscribers as $subscriber) {
            fputcsv($handle, [
                $subscriber->id,
                $subscriber->email,
                $subscriber->ip_address ?? '-',
                $subscriber->subscribed_at ? $subscriber->subscribed_at->format('d/m/Y H:i') : '-',
                $subscriber->is_active ? 'Actif' : 'Inactif',
                $subscriber->unsubscribed_at ? $subscriber->unsubscribed_at->format('d/m/Y H:i') : '-'
            ]);
        }

        fclose($handle);

        return response()->stream(function() use ($handle) {
            // Le contenu est déjà envoyé
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Suppression multiple
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array'
        ]);

        Newsletter::whereIn('id', $request->ids)->delete();

        return response()->json(['success' => true]);
    }
}
