<?php
// app/Http/Controllers/Admin/ClientController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Service;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ClientController extends Controller
{
    /**
     * Afficher la liste des clients
     */
    public function index(Request $request)
    {
        $query = Client::query();

        // Filtre par type de client
        if ($request->filled('type')) {
            if ($request->type === 'company') {
                $query->companies();
            } elseif ($request->type === 'individual') {
                $query->individuals();
            }
        }

        // Filtre par statut
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $clients = $query->ordered()->paginate(15);

        // Statistiques
        $totalClients = Client::count();
        $activeCount = Client::active()->count();
        $companyCount = Client::companies()->count();
        $individualCount = Client::individuals()->count();

        // Calculer le total facturé et le nombre de factures impayées
        $totalInvoiced = Invoice::sum('total');
        $outstandingCount = Invoice::whereIn('status', ['sent', 'pending', 'partially_paid'])->count();

        return view('admin.clients.index', compact(
            'clients',
            'activeCount',
            'totalClients',
            'companyCount',
            'individualCount',
            'totalInvoiced',
            'outstandingCount'
        ));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $services = Service::active()->ordered()->get();
        return view('admin.clients.create', compact('services'));
    }

    /**
     * Enregistrer un nouveau client
     */
    public function store(Request $request)
    {
        $rules = [
            'client_type' => 'required|in:company,individual',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'phone' => 'nullable|string|max:50',
            'gender' => 'nullable|in:M,F',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'contact_name' => 'nullable|string|max:255',
            'contact_position' => 'nullable|string|max:255',
            'billing_cycle' => 'nullable|in:monthly,quarterly,yearly,one_time',
            'invoice_by_email' => 'boolean',
            'is_active' => 'boolean',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'service_ids' => 'nullable|array',
            'service_ids.*' => 'exists:services,id'
        ];

        // Règles spécifiques selon le type
        if ($request->client_type === 'company') {
            $rules['company_name'] = 'required|string|max:255';
            $rules['tax_number'] = 'nullable|string|max:100';
            $rules['website'] = 'nullable|url|max:255';
        }

        $request->validate($rules);

        $data = $request->except('logo', 'service_ids');

        // Gestion du logo
        if ($request->hasFile('logo')) {
            $logoFile = $request->file('logo');
            $logoName = 'client_' . time() . '_' . Str::random(10) . '.' . $logoFile->getClientOriginalExtension();
            $logoPath = $logoFile->storeAs('clients', $logoName, 'public');
            $data['logo'] = $logoPath;
        }

        // Valeurs par défaut
        $data['is_active'] = $request->has('is_active');
        $data['invoice_by_email'] = $request->has('invoice_by_email');
        $data['billing_cycle'] = $request->billing_cycle ?? 'monthly';

        $client = Client::create($data);

        // Associer les services
        if ($request->has('service_ids')) {
            $client->services()->sync($request->service_ids);
        }

        return redirect()->route('admin.clients.index')
            ->with('success', 'Client créé avec succès');
    }

    /**
     * Afficher le détail d'un client
     */
    public function show(Client $client)
    {
        $client->load(['services', 'invoices', 'payments']);

        // Calculer les totaux
        $totalInvoiced = $client->invoices()->sum('total');
        $totalPaid = $client->payments()->where('status', 'completed')->sum('amount');
        $balance = $totalInvoiced - $totalPaid;

        return view('admin.clients.show', compact('client', 'totalInvoiced', 'totalPaid', 'balance'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Client $client)
    {
        $services = Service::active()->ordered()->get();
        $clientServices = $client->services->pluck('id')->toArray();
        return view('admin.clients.edit', compact('client', 'services', 'clientServices'));
    }

    /**
     * Mettre à jour un client
     */
    public function update(Request $request, Client $client)
    {
        $rules = [
            'client_type' => 'required|in:company,individual',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email,' . $client->id,
            'phone' => 'nullable|string|max:50',
            'gender' => 'nullable|in:M,F',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'contact_name' => 'nullable|string|max:255',
            'contact_position' => 'nullable|string|max:255',
            'billing_cycle' => 'nullable|in:monthly,quarterly,yearly,one_time',
            'invoice_by_email' => 'boolean',
            'is_active' => 'boolean',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'service_ids' => 'nullable|array',
            'service_ids.*' => 'exists:services,id'
        ];

        // Règles spécifiques selon le type
        if ($request->client_type === 'company') {
            $rules['company_name'] = 'required|string|max:255';
            $rules['tax_number'] = 'nullable|string|max:100';
            $rules['website'] = 'nullable|url|max:255';
        } else {
            // Pour un particulier, on vide les champs entreprise
            $request->merge([
                'company_name' => null,
                'tax_number' => null,
                'website' => null
            ]);
        }

        $request->validate($rules);

        $data = $request->except('logo', 'service_ids');

        // Gestion du logo
        if ($request->hasFile('logo')) {
            // Supprimer l'ancien logo
            if ($client->logo && Storage::disk('public')->exists($client->logo)) {
                Storage::disk('public')->delete($client->logo);
            }

            $logoFile = $request->file('logo');
            $logoName = 'client_' . time() . '_' . Str::random(10) . '.' . $logoFile->getClientOriginalExtension();
            $logoPath = $logoFile->storeAs('clients', $logoName, 'public');
            $data['logo'] = $logoPath;
        }

        $data['is_active'] = $request->has('is_active');
        $data['invoice_by_email'] = $request->has('invoice_by_email');

        $client->update($data);

        // Mettre à jour les services associés
        if ($request->has('service_ids')) {
            $client->services()->sync($request->service_ids);
        } else {
            $client->services()->sync([]);
        }

        return redirect()->route('admin.clients.index')
            ->with('success', 'Client mis à jour avec succès');
    }

    /**
     * Supprimer un client
     */
    public function destroy(Client $client)
    {
        try {
            // Supprimer le logo
            if ($client->logo && Storage::disk('public')->exists($client->logo)) {
                Storage::disk('public')->delete($client->logo);
            }

            $client->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Client supprimé avec succès'
                ]);
            }

            return redirect()->route('admin.clients.index')
                ->with('success', 'Client supprimé avec succès');

        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('admin.clients.index')
                ->with('error', 'Erreur lors de la suppression');
        }
    }

    /**
     * Activer/Désactiver un client
     */
    public function toggleActive(Client $client)
    {
        $client->update(['is_active' => !$client->is_active]);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $client->is_active ? 'Client activé avec succès' : 'Client désactivé avec succès'
            ]);
        }

        return redirect()->route('admin.clients.index')
            ->with('success', $client->is_active ? 'Client activé' : 'Client désactivé');
    }

    /**
     * Mettre en avant un client (featured)
     */
    public function toggleFeatured(Client $client)
    {
        $client->update(['is_featured' => !$client->is_featured]);

        return response()->json([
            'success' => true,
            'message' => $client->is_featured ? 'Client mis en avant' : 'Client retiré de la une'
        ]);
    }

    /**
     * Réordonner les clients
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:clients,id'
        ]);

        foreach ($request->order as $index => $clientId) {
            Client::where('id', $clientId)->update(['order' => $index + 1]);
        }

        return response()->json(['success' => true, 'message' => 'Ordre mis à jour']);
    }

    /**
     * Exporter les clients en CSV
     */
    public function export()
    {
        $clients = Client::ordered()->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="clients_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($clients) {
            $file = fopen('php://output', 'w');

            // En-têtes CSV
            fputcsv($file, [
                'ID',
                'Type',
                'Nom entreprise',
                'Nom complet',
                'Email',
                'Téléphone',
                'Genre',
                'Adresse',
                'Ville',
                'Pays',
                'IFU',
                'Site web',
                'Contact',
                'Poste',
                'Cycle facturation',
                'Factures par email',
                'Statut',
                'Date création'
            ]);

            // Données
            foreach ($clients as $client) {
                fputcsv($file, [
                    $client->id,
                    $client->client_type_label,
                    $client->company_name,
                    $client->name,
                    $client->email,
                    $client->phone,
                    $client->gender_label,
                    $client->address,
                    $client->city,
                    $client->country,
                    $client->tax_number,
                    $client->website,
                    $client->contact_name,
                    $client->contact_position,
                    $client->billing_cycle,
                    $client->invoice_by_email ? 'Oui' : 'Non',
                    $client->is_active ? 'Actif' : 'Inactif',
                    $client->created_at->format('d/m/Y')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
